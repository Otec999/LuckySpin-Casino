 <!DOCTYPE html>
<html>
<head> 
    <meta charset="utf-8">
    <title>LuckySpin Casino</title>
    <meta name="viewport" content="width=device-width, user-scalable=yes">
    <link rel="stylesheet" type="text/css" href="/css/style.css?v=@php echo time(); @endphp">
    <link rel="stylesheet" type="text/css" href="/css/main.css?v=@php echo time(); @endphp">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800;900&Montserrat&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@800&display=swap" rel="stylesheet">

     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
     
</head>
<body class="theme--dark">
 <div class="body_404">
 	<div class="panel_404">
 		<img src="/images/logotype-dark.png" class="logo" style="width:200px;">
 		<div class="text_404">404</div>
 		<div class="text_1_404">Этой страницы не существует. <br>
Попробуйте снова</div>
	<button class="btn_bet_dice" style="width:154px" onclick="location.href='/'">На главную</button>
 	</div>
 	
 </div>
</body>
 </html>
            </div>
        </div>
        <div class="faq__item">
            <div class="faq__item-heading d-flex align-center">
                <b class="faq__item-question d-flex align-center justify-center">?</b>
                <span>Какая минимальная сумма вывода?</span>
            </div>
            <div class="faq__item-body">
                <p>Минимальная сумма вывода составляет 100Р.</p>
            </div>
        </div>
        <div class="faq__item">
            <div class="faq__item-heading d-flex align-center">
                <b class="faq__item-question d-flex align-center justify-center">?</b>
                <span>Мой вывод отклонён, что делать?</span>
            </div>
            <div class="faq__item-body">
                <p>Скорее всего вы неправильно ввели данные, либо нарушили наши правила.</p>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
   $('.faq__item .faq__item-heading').click(function(e){
    e.preventDefault();
    if($(this).parent().hasClass('faq__item--opened')) {
        $(this).parent().removeClass('faq__item--opened').css({'max-height':'60px'});
    } else {
        $('.faq__item.faq__item--opened').removeClass('faq__item--opened').css({'max-height':'60px'});
        $(this).parent().addClass('faq__item--opened').css({'max-height': $(this).parent()[0].scrollHeight});
    }
});
</script>