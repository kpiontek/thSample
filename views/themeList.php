<div class="themeList">
  <?php foreach($data['themes'] as $theme) { ?>
    <?php $theme['description'] = substr(strip_tags($theme['description']), 0, 250).'...'; ?>
    <?php include('widgets/theme_list_single.php'); ?>
  <?php } ?>
</div>
