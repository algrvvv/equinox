<br>
<form action="/register" method="post" class="form-group mt-25">
    <label for="login_input">Введите логин</label>
    <input class="form-control" type="text" name="login" id="login_input">
    <br><br>

    <label for="email_input">Введите почту</label>
    <input class="form-control" type="email" name="email" id="email_input">
    <br> <br>

    <label for="password_input">Введите пароль</label>
    <input class="form-control" type="password" name="password" id="password_input">
    <br><br>

    <label for="password_confirm_input">Подтвердите пароль</label>
    <input class="form-control" type="password" name="password_confirm" id="password_confirm_input">
    <br><br>

    <button type="submit" class="btn btn-success">Регистрация</button>
</form>

<style>
    form {
        width: 70%;
        margin: 0 auto;
    }
</style>