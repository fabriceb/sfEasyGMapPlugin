<div class="sample-title">Samples list</div>

<ul>
  <?php foreach ($available_samples as $sample): ?>
    <li><?php echo link_to('<span class="link-to-sample">'.$sample['name'].'</span> : '.$sample['message'], $sample['url']) ?></li>
  <?php endforeach; ?>
</ul>
