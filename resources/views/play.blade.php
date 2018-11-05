<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>数独 Sudoku</title>
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/cover.css') }}">
    <script type="text/javascript" src="//code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/play.js?time=').time() }}"></script>
</head>

<body>
    
<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
    <header class="masthead mb-auto">
        <div class="inner text-center">
          <h3 class="masthead-brand">数独 Sudoku</h3>
        </div>
    </header>

  <main role="main" class="inner cover">
    <h1 class="cover-heading">問題 <?=$id ?></h1>
    <br>
    <?php if (!empty(Request::session()->get('message'))) : //有系統訊息 ?>
        <div class="bs-callout bs-callout-<?=Request::session()->get('message_type') //為success、warning、dangers ?>
                    block">
        <h4><?=Request::session()->get('message_type')=='success'?'成功':
                    (Request::session()->get('message_type')=='warning'?'注意':
                    (Request::session()->get('message_type')=='dangers'?'':'エラー')) ?>
        </h4>
          <p><?=Request::session()->get('message') ?></p>
        </div>
    <?php endif; ?>
    <div id="sudoku_container" class="text-center">
        {{ Form::open(array('url' => '/play/correction/'.$id,'id' => 'form_play')) }}
        <div class="sudoku_board" style="width: 398px;">
            <?php foreach ($puzzle as $x => $row) : ?>
                <?php foreach ($row as $y => $col) : ?>
            <div class="cell<?=$col!=0?' fix':'' ?><?=$y%3==2&&$y!=8?' border_v':'' ?>
                        <?=$x%3==2&&$x!=8?' border_h':'' ?>" x="<?=$x+1 ?>" y="<?=$y+1 ?>"  style="height: 44px;">
                    <?php if ($col==0) : ?>
                        <input type="text" name="ans[]" />
                    <?php else : ?>
                        <span style="line-height: 44px;"><?=$col ?></span>
                    <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
        {{ Form::close() }}
    </div>
    <br>
    <div id="block_correction">
        <div class="text-center legend" style="display: none">
            <input type="text" class="correct" disabled />： 正解、 <input type="text" class="incorrect" disabled />：不正解。
        </div>
        <div class="text-center block">
            <button id="correction" class="btn btn-primary" >訂正する</button>
        </div>
    </div>
    <br>
    <div id="block_surrender">
        <div class="text-center block">
            <button id="surrender" class="btn btn-primary" >解答する</button>
        </div>
    </div>
    <div id="block_final" class="text-center" style="display: none">
        <p>ご遊びありがとうございました。</p>
        <a href="/play/<?=$id ?>">≫もう一度遊ぶ</a>
        <a class="block" href="/menu">≫メニューへ</a>
    </div>
  </main>

    <footer class="mastfoot mt-auto text-center">
        <div class="inner">
          <p>Made by KIRIN KYO.</p>
        </div>
    </footer>
</div>
<div class="text_delete hidden">
    <span class="delete"></span>
</div>
<div class="text_blank hidden">
    <span class="text-danger"></span>
</div>
<div class="text_correct hidden">
    <span class="text-correct"></span>
</div>
</body>
</html>