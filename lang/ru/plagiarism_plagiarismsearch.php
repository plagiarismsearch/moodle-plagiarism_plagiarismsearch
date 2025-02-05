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
 * @package    plagiarism_plagiarismsearch
 * @author     Alex Crosby developer@plagiarismsearch.com
 * @copyright  @2017 PlagiarismSearch.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// phpcs:disable moodle.Files.LangFilesOrdering.IncorrectOrder
$string['plagiarismsearch'] = 'PlagiarismSearch';
$string['pluginname'] = 'PlagiarismSearch';

$string['auto_check'] = 'Автоматическая проверка';
$string['manual_check'] = 'Ручная проверка';
$string['add_to_storage'] = 'Добавить в Хранилище';

$string['detect_ai'] = 'Поиск ИИ текста';
$string['sources_type'] = 'Источники';
$string['sources_doc_web_storage'] = 'Документ vs Интернет + Хранилище';
$string['sources_doc_web'] = 'Документ vs Интернет';
$string['sources_doc_storage'] = 'Документ vs Хранилище';

$string['report_type'] = 'Тип файла отчета';
$string['report_show_no'] = 'Не показывать';
$string['report_show_pdf'] = 'Только PDF отчет';
$string['report_show_html'] = 'Только HTML отчет';
$string['report_show_pdf_html'] = 'PDF и HTML отчеты';

$string['report_language'] = 'Язык отчета';
$string['report_language_default'] = 'По умолчанию (Английский)';
$string['report_language_en'] = 'Английский';
$string['report_language_es'] = 'Испанский';
$string['report_language_ua'] = 'Українська';
$string['report_language_pl'] = 'Польский';
$string['report_language_ru'] = 'Русский';

$string['student_show_percentage'] = 'Разрешить студентам просматривать процент плагиата';
$string['student_show_reports'] = 'Разрешить студентам просматривать отчеты';
$string['student_submit'] = 'Разрешить студентам отправлять документы';
$string['student_resubmit'] = 'Разрешить студентам повторно отправлять документы';
$string['student_resubmit_numbers'] = 'Число повторных отправок';
$string['student_disclosure'] = 'Отображение информации о студенте';
$string['student_disclosure_default'] = 'Все загруженные файлы будут отправлены в сервис проверки PlagiarismSearch.com.';
$string['student_error_nopermission'] = 'Нет разрешения';

$string['api_url'] = 'API url';
$string['api_key'] = 'API ключ';
$string['api_user'] = 'API пользователь';
$string['api_debug'] = 'API отладка';
$string['api_version'] = 'API версия';
$string['filter_chars'] = 'Только латинские символы';
$string['filter_references'] = 'Исключить библиографию';
$string['filter_quotes'] = 'Исключить цитаты';

$string['filter_plagiarism'] = 'Исключить само-плагиат';
$string['filter_plagiarism_no'] = 'Нет (не рекомендовано)';
$string['filter_plagiarism_user_course'] = 'Исключить плагиат пользователя в рамках одного курса';
$string['filter_plagiarism_user'] = 'Исключить плагиат пользователя';
$string['filter_plagiarism_course'] = 'Исключить плагиат курса (не рекомендуется)';

$string['parse_text_url'] = 'Разрешить парсинг URL в тексте';
$string['valid_parsed_text_url'] = 'Список допустимых URL для парсинга';

$string['submit'] = 'Проверить на PlagiarismSearch';
$string['resubmit'] = 'Проверить повторно на PlagiarismSearch';
$string['processing'] = 'В процессе';
$string['unknown_error'] = 'Неизвестная ошибка';

$string['pdf_report'] = 'Загрузить PDF отчет';
$string['html_report'] = 'Просмотреть HTML отчет';
$string['link_title'] = 'PlagiarismSearch.com – расширенная онлайн-проверка на плагиат';
$string['check_status'] = 'Проверить статус';
$string['temp_folder_not_exists'] = 'Временная папка не существует';
$string['server_connection_error'] = 'Проблема с подключением к серверу PlagiarismSearch';
$string['submit_ok'] = 'Документ \'{$a}\' отправлен в PlagiarismSearch';
$string['submit_error'] = 'Документ \'{$a}\' не отправлен';
$string['submit_onlinetext_ok'] = 'Онлайн-текст был отправлен в PlagiarismSearch';
$string['submit_onlinetext_error'] = 'Онлайн-текст не был отправлен';
$string['status_ok'] = 'Документ';
$string['status_error'] = 'Ошибка. Документ';
$string['status'] = 'статус';
$string['is_in'] = 'находится в';
$string['plagiarism'] = 'Плагиат';
$string['ai'] = 'ИИ';
$string['ai_rate'] = 'Общий процент ИИ';
$string['ai_probability'] = 'Вероятность ИИ';
$string['empty_parameter'] = 'Пустой \'{$a}\' параметр';
$string['report_not_found'] = 'Отчет не найден';
$string['no_cmid_or_id'] = 'Нет cmid или id';
$string['api_error'] = 'Ошибка PlagiarismSearch API';

$string['report'] = 'Отчет';
$string['save'] = 'Сохранить';
$string['settings_error'] = 'При обновлении настроек PlagiarismSearch произошла ошибка';
$string['settings_error_server'] = 'Ошибка API настроек PlagiarismSearch';
$string['settings_saved'] = 'Настройки PlagiarismSearch успешно сохранены';
$string['submit'] = 'Отправить в PlagiarismSearch';
$string['use'] = 'Включить PlagiarismSearch';
$string['enabled'] = 'Включить PlagiarismSearch';
$string['yellow'] = 'Желтый уровень плагиата начинается с';
$string['red'] = 'Красный уровень плагиата начинается с';

$string['text_plain'] = 'PlagiarismSearch.com – расширенная онлайн-проверка на плагиат. <br/>
PlagiarismSearch.com это ведущий веб-сайт по проверке плагиата, который предоставит вам
точный отчет в течение короткого периода времени. <br/>
Узнайте, как зарегистрироваться <a href="https://plagiarismsearch.com/account/signup">сдесь</a>
и обратитесь к нам за бесплатной пробной версией <a href="mailto:support@plagiarismsearch.com">support@plagiarismsearch.com</a><br/><br/>';

$string['plagiarismsearch:viewlinks'] = 'Возможность просматривать ссылки по результатам плагиата';
$string['plagiarismsearch:submitlinks'] = 'Возможность отправлять ссылки на PlagiarismSearch.com';
$string['plagiarismsearch:statuslinks'] = 'Возможность проверить статус отчета';
$string['plagiarismsearch:isstudent'] = 'Разрешить только студентам';
// phpcs:enable moodle.Files.LangFilesOrdering.IncorrectOrder
