<!--<title>Домашняя страница</title>-->

<?php

use Imissher\Equinox\app\core\Application;

Application::isGuest();

?>

<div class="sm:flex sm:justify-center sm:items-center">
    <div class="max-w-7xl mx-auto p-6 lg:p-8">

        <div class="mt-16">
            <div class="grid grid-cols-1 md:grid-cols-1 gap-6 lg:gap-1">
                <a href="https://github.com/algrvvv/equinox"
                   class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                    <div>
                        <div class="h-16 w-16 bg-red-50 dark:bg-red-800/20 flex items-center justify-center rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100"
                                 viewBox="0,0,256,256">
                                <g fill="#ef4444" fill-rule="nonzero" stroke="none" stroke-width="1"
                                   stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10"
                                   stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none"
                                   font-size="none" text-anchor="none" style="mix-blend-mode: normal">
                                    <g transform="scale(4,4)">
                                        <path d="M32,10c-12.15,0 -22,9.85 -22,22c0,12.15 9.85,22 22,22c12.15,0 22,-9.85 22,-22c0,-12.15 -9.85,-22 -22,-22zM32,14c9.941,0 18,8.059 18,18c0,8.23871 -5.54128,15.16934 -13.0957,17.30664c-0.0928,-0.19124 -0.15645,-0.40072 -0.15039,-0.63867c0.031,-1.209 0,-4.03041 0,-5.06641c0,-1.778 -1.125,-3.03906 -1.125,-3.03906c0,0 8.82422,0.09959 8.82422,-9.31641c0,-3.633 -1.89844,-5.52539 -1.89844,-5.52539c0,0 0.9973,-3.87844 -0.3457,-5.52344c-1.505,-0.163 -4.20056,1.43755 -5.35156,2.18555c0,0 -1.82342,-0.74805 -4.85742,-0.74805c-3.034,0 -4.85742,0.74805 -4.85742,0.74805c-1.151,-0.748 -3.84656,-2.34755 -5.35156,-2.18555c-1.342,1.645 -0.3457,5.52344 -0.3457,5.52344c0,0 -1.89844,1.89044 -1.89844,5.52344c0,9.416 8.82422,9.31836 8.82422,9.31836c0,0 -1.00476,1.14381 -1.10547,2.7832c-0.58969,0.20793 -1.39349,0.45313 -2.16016,0.45313c-1.85,0 -3.25548,-1.79691 -3.77148,-2.62891c-0.508,-0.821 -1.54948,-1.50977 -2.52148,-1.50977c-0.64,0 -0.95312,0.3215 -0.95312,0.6875c0,0.366 0.89823,0.62083 1.49023,1.29883c1.248,1.43 1.22488,4.64648 5.67188,4.64648c0.5258,0 1.47056,-0.1211 2.22461,-0.22461c-0.00417,1.00955 -0.0159,1.97778 0,2.59766c0.00586,0.23869 -0.05897,0.44894 -0.15234,0.64063c-7.55349,-2.1379 -13.09375,-9.0686 -13.09375,-17.30664c0,-9.941 8.059,-18 18,-18z"></path>
                                    </g>
                                </g>
                            </svg>
                        </div>

                        <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Репозиторий на GitHub</h2>

                        <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                            Equinox - PHP Framework, который разработан в целях обучения и понимания работы, как PHP,
                            так и всей веб-разработки.
                            Полная инструкция по установке и работе с данным фреймворком вы найдете в репозитории этого
                            проекта.
                            Проект будет обновляться и всегда ждет ваших предложений и исправлений!
                        </p>
                    </div>

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         class="self-center shrink-0 stroke-red-500 w-6 h-6 mx-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"></path>
                    </svg>
                </a>
            </div>

            <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">
                <div class="ml-4 text-center text-sm text-gray-500 dark:text-gray-400 sm:text-right sm:ml-0">
                    Equinox v<?= Application::app_version() ?> (PHP v<?= phpversion() ?>)
                </div>
            </div>
        </div>
    </div>