class FeedbackControl
{
    constructor(nodeId)
    {
        window.__FC = this;
        this.element = $(nodeId);

        this.all_fields = [
            "action",
            "question_is_about",
            "question",
            "name",
            "phone",
            "email",
            "organisation",
            "job",
            "captcha"
        ]
        this.required_fields = [
            "question_is_about",
            "question",
            "name",
            "phone",
            "email",
            "captcha"
        ];
        this.lastCaptchaUpdate = 0;
        this.element.find("[name=phone]").mask("+7 (999) 999 99-99");
        this.updateCaptcha(true);
        this.addEvents();
    }

    addEvents()
    {
        var el = this.element;

        el.find("#send").on("click", function(ev) {
            var el = $(ev.target);

            var errors = [];
            var data = {};
            this.all_fields.forEach(fieldName => {
                data[fieldName] = this.element.find("[name=" + fieldName + "]").val();
            });

            this.required_fields.forEach(fieldName => {
                if ((fieldName != "question_is_about" && data[fieldName].trim().length == 0) || (fieldName == "question_is_about" && data[fieldName] == null))
                {
                    var label = this.element.find("[data-field-name=" + fieldName + "]").find(".feedback-form__label").attr("data-label-value");
                    errors.push("Поле '" + label + "' обязательно для заполнения");

                    this.setValidationError(fieldName);
                }
                else
                {

                    this.setValidationError(fieldName, false);
                }
            });

            this.all_fields.forEach(fieldName => {
                var fieldBlock = this.element.find("div [data-field-name=" + fieldName +"]");

                var fieldLabel = fieldBlock.find("label").attr("data-label-value");

                if (!this.validateField(fieldBlock.find("[name=" + fieldName +"]")[0]))
                {
                    this.setValidationError(fieldName, true, false);
                    errors.push("Значение поля '" + fieldLabel +"' слишком длинное");
                }
            });

            var $errorsBlock = this.element.find("#feedback-form-errors");
            var $errorsList = $errorsBlock.find(".feedback-form__badfields-list");
            $errorsList.html("");
            if (errors.length > 0)
            {
                errors.forEach(error => {
                    $errorsList.append("<li>" + error + "</li>");
                });

                $errorsBlock.animate({opacity: 1});
                return;
            }
            $errorsList.html("");
            $errorsBlock.css({opacity: 0});

            $.post("/backend/send.php", data, function(response) {
                if (response.success)
                {
                    window.alert("Успешно отправлено! Вы получите уведомление на почту");
                    this.all_fields.forEach(fieldName => {
                        var field = this.element.find("[name=" + fieldName + "]");
                        if (fieldName == "question_is_about")
                        {
                            field.val("-1");
                        }
                        else
                        {
                            field.val("");
                        }
                    });
                }
                else
                {
                    window.alert(response.error);
                }

                this.updateCaptcha(true);
            }.bind(this), "json");
        }.bind(this));

        el.find("#captcha-update-btn").on("click", function(ev) {
            var el = $(ev.target);

            this.updateCaptcha();
        }.bind(this));

        el.find("#agreement").on("change", function(ev) {
            var el = $(ev.target);

            this.element.find("#send").prop("disabled", !el.prop("checked"));
        }.bind(this));
    }

    setValidationError(field, unfilled =true, showError = true)
    {
        var $field = this.element.find("div [data-field-name=" + field +"]");
        if ($field.length > 0)
        {
            var $input = $field.find('.feedback-form__field-input');
            var $label = $field.find('label');
            var $error_text = $field.find('.feedback-form__error');

            if (unfilled)
            {
                if (!$input.hasClass('error'))
                {
                    $input.addClass('error');
                }
                if (!$label.hasClass('error'))
                {
                    $label.addClass('error');
                }

                if (showError)
                {
                    $error_text.animate({opacity: 1});
                }
                else
                {
                    $error_text.css({opacity: 0});
                }
            }
            else
            {
                if ($input.hasClass('error'))
                {
                    $input.removeClass('error');
                }
                if ($label.hasClass('error'))
                {
                    $label.removeClass('error');
                }
                $error_text.css({opacity: 0});
            }
        }
    }

    updateCaptcha(force = false)
    {
        if (Math.floor((new Date).getTime() / 1000) - this.lastCaptchaUpdate <= 5 && !force)
        {
            return;
        }

        if (!force)
        {
            this.lastCaptchaUpdate = Math.floor((new Date).getTime() / 1000);
        }

        this.element.find("#captcha_img").attr("src", "/backend/captcha.php?v=" + Math.random());
        this.element.find("[name=captcha]").val("");
    }

    validateField(node)
    {
        if (typeof node == "undefined")
        {
            return true;
        }
        var $element = $(node);

        switch (node.localName)
        {
            case "textarea":
                return $element.val().length <= 4096;

            case "input":
                if ($element.prop("type") == "text" || $element.prop("type") == "email" || $element.prop("type") == "number")
                {
                    var val = $element.val().toString();
                    return val.length <= 255;
                }
                return true;

            default:
                return true;
        }
    }
}