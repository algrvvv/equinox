<?php /** @var $data */ ?>

<div class="sm:flex sm:justify-center sm:items-center">
    <div class="max-w-7xl mx-auto p-6 lg:p-8">

        <div class="mt-16">
            <div class="grid grid-cols-1 md:grid-cols-1 gap-6 lg:gap-1">
                <span class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                    <div class="flex justify-center items-center">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Логин пользователя: <span
                                    class="underline"><?= $data['login'] ?></span></h2>
                    </div>
                    <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed"> Электронная почта пользователя: <span
                                class="underline"><?= $data['email'] ?></span></p>
                </span>
            </div>
        </div>
    </div>
</div>