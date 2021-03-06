<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.2.43/jquery.form-validator.min.js"></script>
<script>

    var myLanguage = {
        errorTitle: 'Form submission failed!',
        requiredFields: 'Вы не заполнили все обязательные поля',
        badTime: 'Вы ввели некорректное время',
        badEmail: 'Вы ввели некорректный адрес электронной почты',
        badTelephone: 'Вы ввели некорректный номер телефона',
        badSecurityAnswer: 'вы не правильно ответили на вопрос',
        badDate: 'Вы ввели некорректную дату',
        lengthBadStart: 'Введенное значение должно содержать ',
        lengthBadEnd: ' символов',
        lengthTooLongStart: 'Введенное значение больше чем ',
        lengthTooShortStart: 'Введенное значение меньше чем ',
        notConfirmed: 'Введенные значения не совпадают',
        badDomain: 'Некорректное имя домена',
        badUrl: 'Введенное значение является некорректной ссылкой',
        badCustomVal: 'Введенное значение некорректно',
        andSpaces: ' и пробелы ',
        badInt: 'Введенное значение не является числом',
        badSecurityNumber: 'Your social security number was incorrect',
        badUKVatAnswer: 'Incorrect UK VAT Number',
        badStrength: 'введенный пароль недостаточно сложен',
        badNumberOfSelectedOptionsStart: 'Вы должны выбрать ',
        badNumberOfSelectedOptionsEnd: ' вариант(ы)',
        badAlphaNumeric: 'Значение должно содержать только буквы и цифры ',
        badAlphaNumericExtra: ' и ',
        wrongFileSize: 'Загружаемый файл слишком велик (максимально %s)',
        wrongFileType: 'Допустимы только файлы с расширением %s',
        groupCheckedRangeStart: 'Пожалуйста выберите ',
        groupCheckedTooFewStart: 'Пожалуйста выберите ',
        groupCheckedTooManyStart: 'Пожалуйста выберите максимум ',
        groupCheckedEnd: ' элемент(ы)',
        badCreditCard: 'Некорректный номер карты',
        badCVV: 'Проверьте еще раз CVV-код',
        wrongFileDim : 'неверное расширение файла,',
        imageTooTall : 'the image can not be taller than',
        imageTooWide : 'the image can not be wider than',
        imageTooSmall : 'изображение слишком маленькое',
        min : 'минимум',
        max : 'максимум',
        imageRatioNotAccepted : 'недопустимое сжатие картинки'
    };

    /*
      var myLanguage = {
        errorTitle: '<%%>Form submission failed!<%%>',
        requiredFields: '<%%>You have not answered all required fields<%%>',
        badTime: '<%%>You have not given a correct time<%%>',
        badEmail: '<%%>You have not given a correct e-mail address<%%>',
        badTelephone: '<%%>You have not given a correct phone number<%%>',
        badSecurityAnswer: '<%%>You have not given a correct answer to the security question<%%>',
        badDate: '<%%>You have not given a correct date<%%>',
        lengthBadStart: '<%%>The input value must be between <%%>',
        lengthBadEnd: '<%%> characters<%%>',
        lengthTooLongStart: '<%%>The input value is longer than <%%>',
        lengthTooShortStart: '<%%>The input value is shorter than <%%>',
        notConfirmed: '<%%>Input values could not be confirmed<%%>',
        badDomain: '<%%>Incorrect domain value<%%>',
        badUrl: '<%%>The input value is not a correct URL<%%>',
        badCustomVal: '<%%>The input value is incorrect<%%>',
        andSpaces: '<%%> and spaces <%%>',
        badInt: '<%%>The input value was not a correct number<%%>',
        badSecurityNumber: '<%%>Your social security number was incorrect<%%>',
        badUKVatAnswer: '<%%>Incorrect UK VAT Number<%%>',
        badStrength: '<%%>The password isn\'t strong enough<%%>',
        badNumberOfSelectedOptionsStart: '<%%>You have to choose at least <%%>',
        badNumberOfSelectedOptionsEnd: '<%%> answers<%%>',
        badAlphaNumeric: '<%%>The input value can only contain alphanumeric characters <%%>',
        badAlphaNumericExtra: '<%%> and <%%>',
        wrongFileSize: '<%%>The file you are trying to upload is too large (max %s)<%%>',
        wrongFileType: '<%%>Only files of type %s is allowed<%%>',
        groupCheckedRangeStart: '<%%>Please choose between <%%>',
        groupCheckedTooFewStart: '<%%>Please choose at least <%%>',
        groupCheckedTooManyStart: '<%%>Please choose a maximum of <%%>',
        groupCheckedEnd: '<%%> item(s)<%%>',
        badCreditCard: '<%%>The credit card number is not correct<%%>',
        badCVV: '<%%>The CVV number was not correct<%%>',
        wrongFileDim : '<%%>Incorrect image dimensions,<%%>',
        imageTooTall : '<%%>the image can not be taller than<%%>',
        imageTooWide : '<%%>the image can not be wider than<%%>',
        imageTooSmall : '<%%>the image was too small<%%>',
        min : '<%%>min<%%>',
        max : '<%%>max<%%>',
        imageRatioNotAccepted : '<%%>Image ratio is not accepted<%%>'
    };
*/


</script>