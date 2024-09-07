<?php

define("ROOT_DIR", dirname(__FILE__));

require ROOT_DIR . "/backend/functions.php";
?>
<html>
    <head>
        <title>Форма обратной связи</title>
        <meta charset="utf-8">
        <?php addCssFile("/static/css/style.css"); ?>
        <?php addJsFile("/static/js/jquery-3.7.1.min.js"); ?>
        <?php addJsFile("/static/js/jquery.maskedinput.js"); ?>
        <?php addJsFile("/static/js/FeedbackControl.js"); ?>
        <?php addJsFile("/static/js/index.js"); ?>
    </head>
    <body>
        <div class="feedback-form__container" id="feedback-form-container">
            <div id="feedback-form-errors" class="errors" style="opacity: 0;">
                <span class="feedback-form__badfields">Некоторые поля заполнены некорректно</span>

                <ul class="feedback-form__badfields-list">

                </ul>
            </div>

            <div id="feedback-form" class="feedback-form">
                <input type="hidden" name="action" value="message">

                <?php fieldStart("question_is_about") ?>
                    <?php label("Вопрос о...", true) ?>
                    <select name="question_is_about" class="feedback-form__field-input">
                        <option value="-1" disabled selected>Выберите вариант ответа</option>
                        <?php foreach (getQuestionsList() as $optionId => $value): ?>
                            <option value="<?php echo $optionId; ?>"><?php echo htmlspecialchars($value); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php errorFor("question_is_about") ?>
                <?php fieldEnd() ?>

                <?php fieldStart("question") ?>
                    <?php label("Мой вопрос", true) ?>
                    <textarea name="question" class="feedback-form__field-input"></textarea>
                    <?php errorFor("question") ?>
                <?php fieldEnd() ?>

                <?php fieldStart("name") ?>
                    <?php label("ФИО", true) ?>
                    <input type="text" name="name" class="feedback-form__field-input">
                    <?php errorFor("name") ?>
                <?php fieldEnd() ?>

                <?php fieldStart("phone") ?>
                    <?php label("Телефон", true) ?>
                    <input type="text" name="phone" class="feedback-form__field-input">
                    <?php errorFor("phone") ?>
                <?php fieldEnd() ?>

                <?php fieldStart("email") ?>
                    <?php label("E-Mail", true) ?>
                    <input type="email" name="email" class="feedback-form__field-input">
                    <?php errorFor("email") ?>
                <?php fieldEnd() ?>

                <?php fieldStart("organisation") ?>
                    <?php label("Организация") ?>
                    <input type="text" name="organisation" class="feedback-form__field-input">
                <?php fieldEnd() ?>

                <?php fieldStart("job") ?>
                    <?php label("Профессия") ?>
                    <input type="text" name="job" class="feedback-form__field-input">
                <?php fieldEnd() ?>

                <div class="feedback-form__button-wrapper">
                    <button id="send" class="feedback-form__button">Отправить</button>
                </div>
            </div>
        </div>
    </body>
</html>
