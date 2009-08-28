/**
 * creates an address guesser using Google's geocoding requests
 *
 * @author Johannes Schmitt
 */
sfEasyGMapAddressGuesser = function(formFieldIds, queryFormat, callback)
{
  if (GBrowserIsCompatible())
  {
    this.initialize(formFieldIds, queryFormat, callback);
  }
};

sfEasyGMapAddressGuesser.prototype = 
{
  formFieldIds: [],
  queryFormat: '',
  callback: null,
  searchResponseFor: ['LocalityName', 'ThoroughfareName', 'CountryNameCode', 'CountryName', 'PostalCodeNumber', 'ThoroughfareNumber', 'ThoroughfareNumberSuffix', 'ThoroughfareNumberPrefix', 'AdministrativeAreaName'],
  initialize: function(formFieldIds, queryFormat, callback) 
  {
    var reference = this;
    
    this.formFieldIds = formFieldIds;
    this.queryFormat = queryFormat;
    
    if (typeof callback != 'function')
    {
      alert('Your callback must be a valid function.');
      return;
    }
    this.callback = callback;
    
    // create events
    for (var i=0;i<formFieldIds.length;i++)
    {
      document.getElementById(formFieldIds[i]).onblur = function() {if (reference.hasAddressChanged()) reference.retrieveGuesses()};
      document.getElementById(formFieldIds[i]).onkeyup = function() {if (reference.hasAddressChanged()) reference.retrieveGuesses()};
    }
  },
  hasAddressChanged: function()
  {
    var changed = false;
    
    for (var i=0;i<this.formFieldIds.length;i++)
    {
      var inputElem = document.getElementById(this.formFieldIds[i]);
    
      if (typeof inputElem.oldValue == 'undefined' || inputElem.oldValue != inputElem.value)
      {
        changed = true;
        inputElem.oldValue = inputElem.value;        
      }
    }
    
    return changed;
  },
  retrieveGuesses: function()
  {
    var reference = this;
  
    // form the query
    var query = this.queryFormat;
    var num = 0;
    var start = query.search(/%s/);
    while (start != -1)
    {
      query = query.slice(0, start) + document.getElementById(this.formFieldIds[num]).value.replace('%', '') + query.slice(start+2);
    
      num += 1;
      start = query.search(/%s/);
    }
    
    // retrieve guesses
    var geocoder = new GClientGeocoder();
    geocoder.getLocations(query, function(response) {
      reference.parseResponse(response);
    });    
  },
  parseResponse: function(response)
  {
    var addresses = [];
    
    if (response && response.Status.code == 200)
    { 
      for (var i=0;i<response.Placemark.length;i++)
      {
        addresses[addresses.length] = {
          'address': response.Placemark[i].address,
          'addressDetails': this.cleanUpInformation(this.extractInformation(response.Placemark[i])),
          'accuracy': response.Placemark[i].AddressDetails.Accuracy,
          'point': response.Placemark[i].Point
        };
      }
    }
    
    this.callback(addresses);
  },
  extractInformation: function(obj)
  {
    var returnObj = {};
    
    for (var key in obj)
    {
      if (typeof obj[key] == 'object')
      {
        var rExtracted = this.extractInformation(obj[key]);
        for (var sKey in rExtracted)
        {
          returnObj[sKey] = rExtracted[sKey];
        }
        
        continue;
      }    
      
      if (this.inArray(key, this.searchResponseFor) == true)
      {
        returnObj[key] = obj[key];
      }
    }
    
    return returnObj;
  },
  /**
   * This function does some country specific clean-up of the extracted information.
   * It has been tested with German addresses in particular and might need some
   * specific improvements to work with other countries' addresses as well.
   */
  cleanUpInformation: function(obj)
  {
    var cleanedUp = {};
    for (var i=0;i<this.searchResponseFor.length;i++)
      cleanedUp[this.searchResponseFor[i]] = null;
    
    // merge objects
    for (var key in obj)
    {
      switch (key)
      {
        case 'ThoroughfareName':
          // extract number pre-/suffix from the thoroughfare name
          var regex = new RegExp('([a-zA-Z]*)([0-9]+)([a-zA-Z]*)');
          var rs = regex.exec(obj.ThoroughfareName);
          if (rs)
          {
            cleanedUp.ThoroughfareName = this.trim(obj.ThoroughfareName.replace(rs[0], ''));
            cleanedUp.ThoroughfareNumber = rs[2];
            if (rs[1] != '')
              cleanedUp.ThoroughfareNumberPrefix = rs[1];
            if (rs[3] != '')
              cleanedUp.ThoroughfareNumberSuffix = rs[3];
              
            break;            
          }
          
        default:
          cleanedUp[key] = obj[key];
      }    
    }
    
    return cleanedUp;
  },
  trim: function(string)
  {
    return string.replace(/^\s+/, '').replace(/\s+$/, '');
  },
  inArray: function(needle, myArray, caseInSensitive)
  {
    if (caseInSensitive)
      needle = needle.toLowerCase();
  
    for (var i=0;i<myArray.length;i++)
    {
      if (caseInSensitive)
      {
        if (myArray[i].toLowerCase() == needle)
          return true;
      }
      else
      {
        if (myArray[i] == needle)
          return true;
      }
    }
    
    return false;
  }
};
