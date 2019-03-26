<div class="pagenation">
  <ul class="pagenation-list">

    <?php return pagenation($dbProductData['total_page'],$currentPageNum);
    debug('データが返されているかチェック:'.print_r($minPageNum, true));
    ?>

    <!-- １ページ目へのリンク -->
    <?php if($currentPageNum !== 1 ):?>
      <li class="list-item"><a href="?p=1">&lt;</a></li>
    <?php endif; ?>

    <?php for($i = $minPageNum; $i <= $maxPageNum; $i++): ?>
      <li class="list-item"><a href=?p="<?php echo $i;  ?>"><?php echo $i; ?></a></li>
    <?php
      endfor;
     ?>

     <!-- 最終ページへのリンク -->
     <?php if($currentPageNum !== $maxPageNum): ?>
       <li class="list-item"><a href="?p=<?php echo $maxPageNum; ?>">&gt;</a></li>
     <?php endif; ?>
  </ul>

</div>
