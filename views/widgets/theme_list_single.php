<ul id="theme<?= $theme['theme_id']; ?>">
  <li class="votes"><span class="arrowUp"></span><div class="voteVal"><?= $theme['votes']; ?></div><span class="arrowDown"></span></li>
  <li class="thumbnail"><img src="/assets/img/thumbnails/theme<?= $theme['theme_id']; ?>.jpg" /></li>
  <li class="title"><div><a href="/themes/view/<?= $theme['theme_id']; ?>"><?= $theme['title']; ?></a><p><?= $theme['description']; ?></p></div></li>
  <li class="date"><?= date('M j, Y', strtotime($theme['added'])); ?></li>
  <li class="category"><div><?php if (isset($theme['category.parent_title'])) { ?>
  <a href="/themes/category/<?php echo $theme['category.parent_id']; ?>" class="category"><?= $theme['category.parent_title']; ?></a> ><br/> 
  <?php } ?>
  <a href="/themes/category/<?= $theme['category_id']; ?>"><?= $theme['category.title']; ?></a></div></li>
  <li class="price"><div>$<?= $theme['price']; ?></div></li>
</ul>
