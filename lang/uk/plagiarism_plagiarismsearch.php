<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Український переклад
 *
 * @package    plagiarism_plagiarismsearch
 * @author     Alex Crosby developer@plagiarismsearch.com
 * @copyright  @2017 PlagiarismSearch.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// phpcs:disable moodle.Files.LangFilesOrdering.IncorrectOrder
$string['auto_check'] = 'Автоматична перевірка';
$string['manual_check'] = 'Ручна перевірка';
$string['add_to_storage'] = 'Додати до Cховища';

$string['detect_ai'] = 'Виявляти ШІ';
$string['sources_type'] = 'Джерела';
$string['sources_doc_web_storage'] = 'Документ vs Веб + Сховище';
$string['sources_doc_web'] = 'Документ vs Веб';
$string['sources_doc_storage'] = 'Документ vs Сховища';

$string['report_type'] = 'Тип файлу звіту';
$string['report_show_no'] = 'Не показувати';
$string['report_show_pdf'] = 'Тільки PDF звіт';
$string['report_show_html'] = 'Тільки HTML звіт';
$string['report_show_pdf_html'] = 'PDF і HTML звіти';

$string['report_language'] = 'Мова звіту';
$string['report_language_default'] = 'За замовчуванням (англійська)';
$string['report_language_en'] = 'Англійська';
$string['report_language_es'] = 'Іспанська';
$string['report_language_ua'] = 'Українська';
$string['report_language_pl'] = 'Польська';
$string['report_language_ru'] = 'Російська';

$string['student_show_percentage'] = 'Дозволити студентам переглядати відсоток плагіату';
$string['student_show_reports'] = 'Дозволити студентам переглядати звіти';
$string['student_submit'] = 'Дозволити студентам завантажувати роботи на перевірку';
$string['student_resubmit'] = 'Дозволити студентам повторно завантажувати роботи на перевірку';
$string['student_resubmit_numbers'] = 'Кількість повторних завантажень';
$string['student_disclosure'] = 'Короткий опис-інформація для студентів';
$string['student_disclosure_default'] =
        'Усі завантажені файли будуть передані на перевірку плагіату на сервісі PlagiarismSearch.com.';
$string['student_error_nopermission'] = 'Немає дозволу';

$string['api_url'] = 'URL API';
$string['api_key'] = 'Ключ API';
$string['api_user'] = 'Користувач API';
$string['api_debug'] = 'Налагодження (debug) API';
$string['api_version'] = 'Версія API';
$string['filter_chars'] = 'Тільки латинські символи';
$string['filter_references'] = 'Виключити бібліографію';
$string['filter_quotes'] = 'Виключити цитати';

$string['filter_plagiarism'] = 'Виключити самоплагіат';
$string['filter_plagiarism_no'] = 'Ні (не рекомендується)';
$string['filter_plagiarism_user_course'] = 'Виключити плагіат користувача в межах одного курсу';
$string['filter_plagiarism_user'] = 'Виключити плагіат користувача';
$string['filter_plagiarism_course'] = 'Виключити плагіат у межах курсу (не рекомендується)';

$string['parse_text_url'] = 'Дозволити парсинг URL у тексті';
$string['valid_parsed_text_url'] = 'Список дійсних URL для парсингу';

$string['submit'] = 'Подати до PlagiarismSearch';
$string['resubmit'] = 'Повторно подати до PlagiarismSearch';
$string['processing'] = 'Виконується';
$string['unknown_error'] = 'Невідома помилка';

$string['pdf_report'] = 'Завантажити звіт у PDF';
$string['html_report'] = 'Переглянути звіт у HTML';
$string['link_title'] = 'PlagiarismSearch.com – розширений онлайн-інструмент для перевірки плагіату';
$string['check_status'] = 'Перевірити статус';
$string['temp_folder_not_exists'] = 'Тимчасова папка не існує';
$string['server_connection_error'] = 'Проблема з підключенням до сервера PlagiarismSearch';
$string['submit_ok'] = 'Документ \'{$a}\' подано до PlagiarismSearch';
$string['submit_error'] = 'Документ \'{$a}\' не подано';
$string['submit_onlinetext_ok'] = 'Онлайн-текст подано до PlagiarismSearch';
$string['submit_onlinetext_error'] = 'Онлайн-текст не подано';
$string['status_ok'] = 'Документ';
$string['status_error'] = 'Помилка. Документ';
$string['status'] = 'статус';
$string['is_in'] = 'знаходиться в';
$string['plagiarism'] = 'Плагіат';
$string['ai'] = 'ШІ';
$string['ai_rate'] = ' Відсоток ШІ';
$string['ai_probability'] = 'Ймовірність ШІ';
$string['empty_parameter'] = 'Порожній параметр \'{$a}\'';
$string['report_not_found'] = 'Звіт не знайдено';
$string['no_cmid_or_id'] = 'Немає cmid або id';
$string['api_error'] = 'Помилка API PlagiarismSearch';

$string['report'] = 'Звіт';
$string['save'] = 'Зберегти';
$string['settings_error'] = 'Під час оновлення налаштувань PlagiarismSearch сталася помилка';
$string['settings_error_server'] = 'Помилка API налаштувань PlagiarismSearch';
$string['settings_saved'] = 'Налаштування PlagiarismSearch успішно збережено';
$string['use'] = 'Увімкнути PlagiarismSearch';
$string['enabled'] = 'Увімкнути PlagiarismSearch';
$string['yellow'] = 'Рівень плагіату жовтий починається з';
$string['red'] = 'Рівень плагіату червоний починається з';

$string['text_plain'] = 'PlagiarismSearch.com – розширений онлайн-інструмент для перевірки плагіату. <br/>
PlagiarismSearch.com – провідний сайт для перевірки плагіату, який надасть вам точний звіт за короткий час. <br/>
Дізнайтеся, як зареєструватися <a href="https://plagiarismsearch.com/account/signup">тут</a> і попросіть нас про безкоштовну пробну версію <a href="mailto:support@plagiarismsearch.com">support@plagiarismsearch.com</a><br/><br/>';

$string['plagiarismsearch:viewlinks'] = 'Можливість переглядати посилання на результати плагіату';
$string['plagiarismsearch:submitlinks'] = 'Можливість подавати посилання до PlagiarismSearch.com';
$string['plagiarismsearch:statuslinks'] = 'Можливість перевіряти статус звіту';
$string['plagiarismsearch:isstudent'] = 'Дозволити лише студентам';
// phpcs:enable moodle.Files.LangFilesOrdering.IncorrectOrder
