<?php 

//function.phpを読み込んでしまうとセッションをいちいち読みこむことになる
require('function.php');

ini_set('log_errors', 'on');
ini_set('error_log', 'php.log');
// session_start();
error_log('セッション開始します');

//ajaxで画面遷移させるのではなく、オブジェクトにセッションを与えてhtmlの画面を切り替える

$monsters = array();


abstract class  Creature{
    //カプセル化
    protected $name;
    protected $hp;
    protected $img;
    protected $attackMin;
    protected $attackMax;
    public function setName($str){
        $this->name = $str;
    }
    public function getName(){
        return $this->name;
    }
    public function setHp($num){
        $this->hp = $num;
    }
    public function getHp(){
        return $this->hp;
    }
    public function getImg(){
        return $this->img;
    }
    public function attack($targetObj){
        $attackPoint = mt_rand($this->attackMin, $this->attackMax);
        if(!mt_rand(0, 6)){
            $attackPoint = $attackPoint * 1.5;
            //わかりやすくするためにキャストする
            $attackPoint = (int)$attackPoint;
            //クリティカルヒットです！と出させる
            History::set($this->getName().'のクリティカルヒット！');
        }
        //攻撃対象のプロパティにアクセス
        $targetObj->setHp($targetObj->getHp() - $attackPoint);
        History::set($attackPoint.'ポイントのダメージを与えた！');
    }
}

//継承クラス
class Human extends Creature{
    public function __construct($name, $hp, $img, $attackMin, $attackMax){
        $this->name = $name;
        $this->hp = $hp;
        $this->img = $img;
        $this->attackMin = $attackMin;
        $this->attackMax = $attackMax;
    }
}

//継承クラス モンスターはランダムに表示できるようにする
class Monster extends Creature{
    public function __construct($name, $hp, $img, $attackMin, $attackMax){
        $this->name = $name;
        $this->hp = $hp;
        $this->img = $img;
        $this->attackMin = $attackMin;
        $this->attackMax = $attackMax;
    }
}

//継承クラス　魔法モンスター
class MagicMonster extends Monster{
    private $magickAttack;
    //親要素を継承
    public function __construct($name, $hp, $img, $attackMin, $attackMax, $magickAttack){
        parent::__construct($name, $hp, $img, $attackMin, $attackMax);
        $this->magicAttack = $magickAttack;
    }
    public function getMagickAttack(){
        return $this->magicAttack;
    }
    //攻撃メソッドをオーバーライド 親要素Creatureの攻撃メソドを殺す
    public function attack($targetObj){
        //１０回中１回は魔法攻撃
        if(!mt_rand(0, 9)){
            //〜〜の魔法攻撃
            History::set($this->name.'の魔法攻撃！');
            $targetObj->setHp($targetObj->getHp() - $this->magicAttack);
            //〜〜ポイントのダメージを受けた！
            History::set($this->magicAttack.'ポイントのダメージを与えた！');
        }else{
            parent::attack($targetObj);
        }
    }
}

//抽象クラスCratureを作る前のインターフェースを用意
interface HistoryInterface {
    //コンストラクタは生成しないので　静的メンバとする 手軽にアクセス可能になる
    public static function clear();
    public static function set($str);
}
//インターフェースを実装
class History implements HistoryInterface{
    //セッションを付与
    public static function set($str){
        if(empty($_SESSION['history'])) $_SESSION['history'] = '';
        $_SESSION['history'] .= $str.'<br>';
    }
    public static function clear(){
        unset($_SESSION['history']);
    } 
}



//インスタンス生成
$human  = new Human('ユーザー', 100, 'img/sippo2.png', 10, 15);
$monsters[] = new Monster('ザコ3つ目', 20, 'img/ahegon.png',10, 20);
$monsters[] = new Monster('タコ', 20 , 'img/obatori.png', 10, 20);
$monsters[] = new MagicMonster('ケルベロス', 20, 'img/inuvelos.png', 15, 25, 30);


//ただのメソッド
function createHuman(){
   global $human;
//    debug('ユーザー画像チェク2:'.print_r($human, true));
   $_SESSION['human'] = $human;
//    debug('ユーザーの格納データ:'.print_r($_SESSION['human'], true));
}
function createMonster(){
    global $monsters;
    $monster = $monsters[mt_rand(0, 2)];
    $_SESSION['monster'] = $monster;
    error_log('モンスターが現れた！');
    // debug('モンスターの格納データ:'.print_r($_SESSION['monster'], true));
}
function init(){
    History::clear();
    History::set('初期化します！');
    createHuman();
    createMonster();
}
function gameOver(){

}


$startFlg = (!empty($_POST['start']))? true : false;
$attackFlg = (!empty($_POST['attack']))? true : false;

if($startFlg){
    error_log('ゲームスタートします');
    init();
}

if($attackFlg){
    error_log('攻撃します');

    History::set($_SESSION['human']->getName().'の攻撃！');
    $_SESSION['human']->attack($_SESSION['monster']);

    History::set($_SESSION['monster']->getName().'の攻撃！');
    $_SESSION['monster']->attack($_SESSION['human']);

    if($_SESSION['human']->getHp() <= 0){
        error_log('貴方のHPがゼロになりました');
        
    }else{
        if($_SESSION['monster']->getHp() <= 0){
            error_log('モンスターを倒しました');
            History::set('倒しました！！おめでとう！！');

            error_log('仮のログインさせます');
            $sesLimit = 60*60;

            $_SESSION['login_date'] = time();
            $_SESSION['login_limit'] = $sesLimit;
            
            header("Location:mypage.php");
        }
    }
}




// if(!empty($_POST)){
//     error_log('POSTがあります');
//     $startFlg = (!empty($_POST['start']))? true : false;
//     $attackFlg = (!empty($_POST['attack']))? true : false;

//     if($startFlg){
//         error_log('初期化します！');
//         init();
//     }else{
//         if($attackFlg){
//             error_log('アタックフラグがあります');
//             error_log('攻撃します');
//             History::set($_SESSION['human']->getName().'の攻撃！');
//             $_SESSION['human']->attack($_SESSION['monster']);
//             error_log('モンスターのHP:'.print_r($_SESSION['monster'], true));

//             History::set($_SESSION['monster']->getName().'の攻撃！');
//             $_SESSION['monster']->attack($_SESSION['human']);
//             error_log('ユーザーのHP:'.print_r($_SESSION['human'], true));
            
            
//             if($_SESSION['human']->getHp() <= 0){
//                 error_log('ゲームオーバーです');
//                 History::set($_SESSION['human']->getName().'は負けました！');
//             }else{
//                 if($_SESSION['monster']->getHp() <= 0){
//                     error_log('勝利！');
//                     History::set($_SESSION['monster']->getName().'を倒した！');

//                     header("Location:index.php");
//                 }
//             }
//         }
//     }
// }


// $startFlg = (!empty($_POST['start']))? true : false;
// if($startFlg){
//     init();
//     error_log('初期化します');
// }


// $attackFlg = (!empty($_POST['attack']))? true : false;
// if($attackFlg){
//     error_log('攻撃があります');
//     History::set($_SESSION['human']->getName().'の攻撃!');
//     $_SESSION['human']->attack($_SESSION['monster']);
//     error_log('モンスタ-のHP:'.print_r($_SESSION['monster'], true));

//     History::set($_SESSION['monster']->getName().'の攻撃！');
//     $_SESSION['monster']->attack($_SESSION['human']);
//     error_log('ユーザーのHP:'.print_r($_SESSION['human'], true));

//     if($_SESSION['human']->getHp() <= 0){
//         error_log('貴方のHPは0です');
//     }else{
//         if($_SESSION['monster']->getHp() <= 0){
//         error_log('モンスターを倒しました');
//     }
//    }
    
    





//    if(!empty($_POST)){
//        error_log('POSTがあります');
//     //各種ボタンをsubmitにしたのでpostでゲームを操作する
//         $startFlg = (!empty($_POST['start']))? true : false;
//         debug('スタートフラグ:'.print_r($startFlg, true));

//         $attackFlg = (!empty($_POST['attack']))? true : false;
//         debug('攻撃フラグ:'.print_r($attackFlg, true));

//         if($startFlg == true){
//             debug('ゲームスタート');
//             init();
//         }else{
//            if($attackFlg == true){
//             debug('攻撃します');
//             History::set($_SESSION['human']->getName().'の攻撃!');
//             $_SESSION['human']->attack($_SESSION['monster']);

//             History::set($_SESSION['monster']->getName().'の攻撃！');
//             $_SESSION['monster']->attack($_SESSION['human']);

//             if($_SESSION['human']->getHp() <= 0){
//                 //ゲームオーバー
//                 History::set('ゲームオーバーです。やりなおしてください');
//             }else{
//                 if($_SESSION['monster']->getHp() <= 0){
//                     History::set('勝利！');                
//                 }
//             }
//         }
//     }
//   }

        
 //リセット
if(!empty($_POST['reset'])){
    error_log('リセットしました');
    unset($_SESSION);
}

?>







<?php
 $siteTitle = '簡易ログイン';
 require('head.php');
 ?>

  <body class="page-login-game page-2colum">
    
    <?php
     require('header.php');
     ?>


     <div class="site-width" id="contents">
       
       <div id="main" style="margin-left:90px;">
        <section class="object-login-wrap" style="<?php if(!empty($_SESSION)){ echo 'float: left;'; } ?>">
         <?php if(empty($_SESSION)){ ?>
          <div id="start-wrap">
           <div  id="start-select">
            <h1 class="game-title" style="margin-bottom:30px; text-align:center;">ログインゲームを始めますか？</h1>
            <form method="post">
              <input type="submit" class="btn-start" name="start" value="はい">
            </form>
            <a href="login.php"><input type="submit" value="いいえ"></a>
           </div>
          </div>
         <?php } ?>

         <?php if(!empty($_SESSION)){ ?>
            <div id="game-wrap">
             <div class="game-display">
               <div id="monster-name-container">
                <h1 class="monster-title"><?php echo $_SESSION['monster']->getName().'が現れた!'; ?></h1>
               </div>
               <img class="monster-img" src="<?php echo $_SESSION['monster']->getImg(); ?>" alt="">
               <img class="human-img" src="<?php echo $_SESSION['human']->getImg(); ?>" alt="">
               
             </div>
             <div class="game-select">
               <form method="post">
                <input class="btn-atk" type="submit" name="attack" value="攻撃！" style=" font-size:40px; border-radius: 300px; height:200px; margin-top:60px;">
               </form>
               <div class="history-container">

               </div>
             </div>
             <form method="post">
                 <input type="submit" name="reset" value="やりなおす" style="width:200px;">
             </form>
            </div>

         <?php } ?>
        </section>

        <?php if(!empty($_SESSION)){ ?>
         <div class="game-history">
          <p><?php if(!empty($_SESSION['history'])){ echo $_SESSION['history'];} ?></p>
         </div>
        <?php } ?>
        


       </div>


     </div>

    <?php
     require('footer.php');
     ?>
  </body>
</html>
