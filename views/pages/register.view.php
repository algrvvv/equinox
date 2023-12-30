<h1 class="m-6 text-3xl font-semibold text-gray-900 dark:text-white text-center">Регистрация</h1>

<form action="/register" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 w-1/2 mx-auto">

    <div class="mt-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="login_input">Введите логин</label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
               type="text" name="login" id="login_input">
    </div>

    <div class="mt-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="email_input">Введите почту</label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
               type="email" name="email" id="email_input">
    </div>

    <div class="mt-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="password_input">Введите пароль</label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
               type="password" name="password" id="password_input">
    </div>

    <div class="mt-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="password_confirm_input">Подтвердите
            пароль</label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
               type="password" name="password_confirm" id="password_confirm_input">
    </div>

    <button type="submit"
            class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        Регистрация
    </button>
</form>
