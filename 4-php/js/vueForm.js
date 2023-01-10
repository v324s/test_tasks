        var vueForm = new Vue({
            el: "#form_signUp",
            data:{
                user_firstName: "",
                user_lastName: "",
                user_email: ""
            },
            methods:{
                checkForm:function(e){
                    // Отключаем автоматическую отправку формы
                    e.preventDefault();

                    // Валидация Имени
                    if (this.user_firstName !=='' && this.user_firstName.length>=2 && this.user_firstName.length<=30){
                        validFirstName=true;
                        $('#input_firstName').removeClass('is-invalid');
                        $('#input_firstName').addClass('is-valid');
                    }else{
                        validFirstName=false;
                        $('#input_firstName').addClass('is-invalid');
                    }

                    // Валидация Фамилии
                    if (this.user_lastName !=='' && this.user_lastName.length>=2 && this.user_lastName.length<=30){
                        validLastName=true;
                        $('#input_lastName').removeClass('is-invalid');
                        $('#input_lastName').addClass('is-valid');
                    }else{
                        validLastName=false;
                        $('#input_lastName').addClass('is-invalid');
                    }

                    // Валидация e-mail
                    if (this.user_email.match(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)){
                        validEmail=true;
                        $('#input_email').removeClass('is-invalid');
                        $('#input_email').addClass('is-valid');
                    }else{
                        validEmail=false;
                        $('#input_email').addClass('is-invalid');
                    }

                    // Если в процессе валидации, не было обнаружено ошибок, то отправляем запрос на сервер
                    validForm=validFirstName && validLastName && validEmail;
                    if (validForm){
                        this.sendData();
                    }
                },
                sendData:function(){
                    let resp; // Локальная переменная ответа от сервера

                    // заносим данные в параметры
                    const params = { 
                        fname: this.user_firstName,
                        lname: this.user_lastName,
                        email: this.user_email 
                    };

                    // переменная в случае отправки запроса с заголовками
                    const headers = {};

                    // отправка запроса
                    axios.post("engine/signUp.php", params, { headers })
                     .then(response => {
                        resp=response.data; // ответ от сервера

                        // проверяем тип ответа (успешно или с ошибками) и выводим сообщение в модальное окно (уведомление)
                        resp['error']==1 ? $('#modalLabel').text('Error') : $('#modalLabel').text('Success');
                        $('.modal-body').text(resp['text']);
                        $('#showModal').click();
                     })
                     .catch(e => {

                        // в случае неудавшейся отправки запроса
                        console.log(e.response.data);
                        $('#modalLabel').text('Error');
                        $('.modal-body').text('Ошибка отправки запроса');
                        $('#showModal').click();
                     });
                }
                
            }
        })