<div id="message">
  <p>Автор: <?= $messages[$parent_id]['name'] ?> | Дата:<?= $messages[$parent_id]['date'] ?></p>
  <div><?= nl2br(htmlspecialchars($messages[$parent_id]['text'])); ?></div>
<div id="reply">
  <details>
  <summary>reply</summary>
  <form method="post" name="reply" action="">
    <input name="username" type="text" id="username" size="63" /></br>
    <textarea name="comment" id="comment"></textarea></br>
    <button type="submit" name="submit_reply" value="<?=$messages[$parent_id]['msg_id'] ?>">submit</button>
  </form>
  </details>
  </div>
</div>
