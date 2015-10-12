<form method="POST">
  <div><label>URL: </label> <input type="text" name="url" /></div>
  <div><label>Category: </label> <select name="category">
    <?php foreach($data['categories'] as $pid => $category): ?><option value="<?= $pid; ?>"><?= $category['title']; ?></option>
      <?php if (isset($category['children'])): foreach($category['children'] as $cid => $child): ?><option value="<?= $cid; ?>"> - <?= $child['title']; ?></option><?php endforeach; endif; ?>
    <?php endforeach; ?>
  </select></div>
  <div><label>Website: </label> <select name="website">
    <?php foreach($data['websites'] as $website): ?><option value="<?= $website['website_id']; ?>"><?= $website['title']; ?></option><?php endforeach; ?>
  </select></div>
  <input type="submit" name="submit" value="Add Feed" />
</form>