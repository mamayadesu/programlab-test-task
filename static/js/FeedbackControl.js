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
            "job"
        ]
        this.required_fields = [
            "question_is_about",
            "question",
            "name",
            "phone",
            "email"
        ];

        this.element.find("[name=phone]").mask("+7 (999) 999 99-99");
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

            var someFieldsAreUnfilled = false;
            this.required_fields.forEach(fieldName => {
                if ((fieldName != "question_is_about" && data[fieldName].trim().length == 0) || (fieldName == "question_is_about" && data[fieldName] == null))
                {
                    someFieldsAreUnfilled = true;

                    var label = this.element.find("[data-field-name=" + fieldName + "]").find(".feedback-form__label").attr("data-label-value");
                    errors.push("Поле '" + label + "' обязательно для заполнения");

                    this.setUnfilled(fieldName);
                }
                else
                {

                    this.setUnfilled(fieldName, false);
                }
            });

            var $errorsBlock = this.element.find("#feedback-form-errors");
            var $errorsList = $errorsBlock.find(".feedback-form__badfields-list");
            $errorsList.html("");
            if (someFieldsAreUnfilled)
            {
                errors.forEach(error => {
                    $errorsList.append("<li>" + error + "</li>");
                });

                $errorsBlock.animate({opacity: 1});
                return;
            }
            $errorsList.html("");
            $errorsBlock.css({opacity: 0});

            // ToDo
        }.bind(this));


    }

    setUnfilled(field, showError = true)
    {
        var $field = this.element.find("div [data-field-name=" + field +"]");
        if ($field.length > 0)
        {
            var $input = $field.find('.feedback-form__field-input');
            var $label = $field.find('label');
            var $error_text = $field.find('.feedback-form__error');

            if (showError)
            {
                if (!$input.hasClass('error'))
                {
                    $input.addClass('error');
                }
                if (!$label.hasClass('error'))
                {
                    $label.addClass('error');
                }
                $error_text.animate({opacity: 1});
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
}