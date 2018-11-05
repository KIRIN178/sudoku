<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>数独 Sudoku</title>
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/cover.css') }}">
    <script type="text/javascript" src="//code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/menu.js?time=').time() }}"></script>
</head>

<body>
    
<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
    <header class="masthead mb-auto">
        <div class="inner text-center">
          <h3 class="masthead-brand">数独 Sudoku</h3>
        </div>
    </header>

  <main role="main" class="inner cover">
    <h1 class="cover-heading">メニュー</h1>
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
    <br>
    <h3>問題リスト</h3>
    <hr>
    <?php if (count($list) == 0) : ?>
    <p class="text-center">数独の問題がありません。新しい問題を作りましょう。</p>
    <?php else : ?>
    <div class="text-center">
        <label class="d-inline-block">問題リスト：</label>
        {{ Form::open(array('url' => '/menu/question','class' => 'd-inline-block','id' => 'form_play')) }}
        <select id="question_list" class="form-control form-group d-inline-block" name="id">
        <?php foreach ($list as $idx => $question) : ?>
            <option value="<?=$question["id"] ?>" data-id="<?=$question["id"] ?>">
                問題<?=$idx+1 ?> 
                [難度:<?=$question["cell_count"]==48?'素人':($question["cell_count"]==38?'普通人':($question["cell_count"]==30
                                                                                            ?'玄人':'マニアック')) ?>]
            </option>
        <?php endforeach; ?>
        </select>
        {{ Form::close() }}
        <button id="play" class="btn btn-primary" >遊び</button>
        <button id="delete" class="btn btn-primary" >削除</button>
    </div>
    <?php endif; ?>
    <br><br>
    <h3>問題の作成</h3>
    <hr>
    <div class="text-center">
        <label class="d-inline-block">問題の難度：</label>
        {{ Form::open(array('url' => '/menu/question','class' => 'd-inline-block','id' => 'form_create')) }}
        <select class="form-control form-group" name="cell_count">
            <option value="48">素人</option>
            <option value="38">普通人</option>
            <option value="30">玄人</option>
            <option value="27">マニアック</option>
        </select>
        {{ Form::close() }}
        <button id="create" class="btn btn-primary" >作成</button>
    </div>
    <br><br>
    <h3>ユーザー</h3>
    <hr>
    <p class="text-center">
        ユーザーID: <?=$id ?>
    </p>
    <p class="text-center">
        <a id="data_link" href="javascript:void(0);">≫データ引き継ぎ</a>
        <div id="data_link_block" class="form-group text-center" style="display:none;">
            <p>前のユーザーIDを入力してください。</p>
            <p>注意：前のデータは今のユーザーIDに引き継ぎするため、前の装置にデータは消えてしまいます。</p>
            <div class="form-group">
            {{ Form::open(array('url' => '/menu/question','class' => 'd-inline','id' => 'form_inherit')) }}
            <input type="text" class="control-form" name="cookie" />
            {{ Form::close() }}
            <button id="inherit" class="btn btn-primary btn-sm" >引き継ぎ</button>
            </div>
        </div>
    </p>
  </main>

    <footer class="mastfoot mt-auto text-center">
        <div class="inner">
          <p>Made by KIRIN KYO.</p>
        </div>
    </footer>
</div>
<div class="text_msg hidden"><div class="msg"></div></div>
    
</body>
</html>