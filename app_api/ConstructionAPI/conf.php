<?php
/**
 * @name         Main Construction API Configuration File
 * @version      1.0
 * @author       Akila Perera <ravihansa3000@gmail.com>
 * @about        Developed for PrimeEng
 * @lastModified 2014.09.04
 */

// Database Configuration
$conf ['db_name'] = "primetec_privytex_construct";
$conf ['db_host'] = "127.0.0.1";
$conf ['db_port'] = "3306";
$conf ['db_user'] = "primetec_prime_c";
$conf ['db_pass'] = "4DC[.(DV]]&b";
$conf ['app_path'] = realpath ( dirname ( __FILE__ ) );
$conf ['image_path'] = realpath ( $conf ['app_path'] . '/images' );