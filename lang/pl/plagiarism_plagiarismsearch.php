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
 * Polish translation
 *
 * @package    plagiarism_plagiarismsearch
 * @author     Alex Crosby developer@plagiarismsearch.com
 * @copyright  @2017 PlagiarismSearch.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// phpcs:disable moodle.Files.LangFilesOrdering.IncorrectOrder
$string['plagiarismsearch'] = 'PlagiarismSearch';
$string['pluginname'] = 'PlagiarismSearch';

$string['auto_check'] = 'Automatyczna kontrola';
$string['manual_check'] = 'Ręczna kontrola';
$string['add_to_storage'] = 'Dodaj do Magazynu';

$string['detect_ai'] = 'Wykryj SI tekst';
$string['sources_type'] = 'Źródła';
$string['sources_doc_web_storage'] = 'Dokument vs Internet+Pamięć';
$string['sources_doc_web'] = 'Dokument vs Internet';
$string['sources_doc_storage'] = 'Dokument vs Pamięć';

$string['report_type'] = 'Rodzaj pliku raportu';
$string['report_show_no'] = 'Nie pokazuj';
$string['report_show_pdf'] = 'Tylko PDF raport';
$string['report_show_html'] = 'Tylko HTML raport';
$string['report_show_pdf_html'] = 'PDF i HTML raporty';

$string['report_language'] = 'Zgłoś język';
$string['report_language_default'] = 'Domyślnie (Angielski)';
$string['report_language_en'] = 'Angielski';
$string['report_language_es'] = 'Hiszpański';
$string['report_language_ua'] = 'Ukrainian';
$string['report_language_pl'] = 'Polski';
$string['report_language_ru'] = 'Rosyjski';

$string['student_show_percentage'] = 'Pozwól studentom przegląd procentu plagiatu';
$string['student_show_reports'] = 'Pozwól studentom przegląd raportów';
$string['student_submit'] = 'Pozwól studentom wysłanie dokumentów';
$string['student_resubmit'] = 'Pozwól studentom wysłanie dokumentów jeszcze raz';
$string['student_resubmit_numbers'] = 'Liczba wysłań powtarzających';
$string['student_disclosure'] = 'Wyświetl informacje o studencie';
$string['student_disclosure_default'] = 'Wszystkie pliki załadowane skierowane do serwisu kontroli PlagiarismSearch.com.';
$string['student_error_nopermission'] = 'Brak pozwolenia';

$string['api_url'] = 'API url';
$string['api_key'] = 'API klucz';
$string['api_user'] = 'API użytkownik';
$string['api_debug'] = 'API debugowanie';
$string['api_version'] = 'API wersja';
$string['filter_chars'] = 'Tylko symbole łacińskie';
$string['filter_references'] = 'Wyfiltruj bibliografię';
$string['filter_quotes'] = 'Wyfiltruj cytaty';

$string['filter_plagiarism'] = 'Wyfiltruj samo-plagiat';
$string['filter_plagiarism_no'] = 'Nie (niepolecane)';
$string['filter_plagiarism_user_course'] = 'Wyfiltruj plagiat użytkownika w ramach jednego kursu';
$string['filter_plagiarism_user'] = 'Wyfiltruj plagiat użytkownika';
$string['filter_plagiarism_course'] = 'Wyfiltruj plagiat kursu (niepolecane)';

$string['parse_text_url'] = 'Zezwalaj na parsowanie adresów URL w tekście';
$string['valid_parsed_text_url'] = 'Lista prawidłowych adresów URL do analizy';

$string['submit'] = 'Sprawdź na PlagiarismSearch';
$string['resubmit'] = 'Sprawdź jeszcze raz na PlagiarismSearch';
$string['processing'] = 'Opracowanie';
$string['unknown_error'] = 'Nieznany błąd';

$string['pdf_report'] = 'Zapisz PDF raport';
$string['html_report'] = 'Pokaż HTML raport';
$string['review_report'] = 'Recenzowanie raportu';
$string['show_review_link'] = 'Zezwól nauczycielom recenzować raporty';
$string['link_title'] = 'PlagiarismSearch.com – zaawansowane narzędzie do sprawdzania plagiatu online';
$string['check_status'] = 'Sprawdź status';
$string['temp_folder_not_exists'] = 'Folder tymczasowy nie istnieje';
$string['server_connection_error'] = 'Problem podłączenia do serweru PlagiarismSearch';
$string['submit_ok'] = 'Dokument \'{$a}\' wysłany do PlagiarismSearch';
$string['submit_error'] = 'Dokument \'{$a}\' nie wysłany';
$string['submit_onlinetext_ok'] = 'Online tekst wysłany do PlagiarismSearch';
$string['submit_onlinetext_error'] = 'Online tekst nie wysłany';
$string['status_ok'] = 'Dokument';
$string['status_error'] = 'Błąd. Dokument';
$string['status'] = 'status';
$string['is_in'] = 'jest w';
$string['plagiarism'] = 'Plagiat';
$string['ai'] = 'SI';
$string['ai_rate'] = ' Całkowita stawka SI';
$string['ai_probability'] = 'SI Prawdopodobieństwo';
$string['empty_parameter'] = 'Pusty \'{$a}\' parameter';
$string['report_not_found'] = 'Nie znaleziono raportu';
$string['no_cmid_or_id'] = 'Nie ma cmid albo id';
$string['api_error'] = 'Błąd API PlagiarismSearch';

$string['report'] = 'Raport';
$string['save'] = 'Zapisz';
$string['settings_error'] = 'Podczas aktualizacji ustawień PlagiarismSearch wystąpił błąd';
$string['settings_error_server'] = 'Błąd API ustawień PlagiarismSearch';
$string['settings_saved'] = 'Ustawienia PlagiarismSearch pomyślnie zapisane';
$string['use'] = 'Załącz PlagiarismSearch';
$string['enabled'] = 'Załącz PlagiarismSearch';
$string['yellow'] = 'Żółty poziom plagiatu zaczyna się od';
$string['red'] = 'Czerwony poziom plagiatu zaczyna się od';

$string['text_plain'] = 'PlagiarismSearch.com – zaawansowane narzędzie do sprawdzania plagiatu online. <br/>
PlagiarismSearch.com to jest wiodąca strona internetowa kontroli plagiatu, która dostarczy Ci dokładny raport w krótkim czasie. <br/>
Dowiedz się, <a href="https://plagiarismsearch.com/account/signup">jak się zarejestrować</a> i skontaktuj się z nami, aby uzyskać bezpłatną wersję próbną <a href="mailto:support@plagiarismsearch.com">support@plagiarismsearch.com</a><br/><br/>';

$string['plagiarismsearch:viewlinks'] = 'Możliwość przeglądu linków wg wyników plagiatu';
$string['plagiarismsearch:submitlinks'] = 'Możliwość wysłania linków na PlagiarismSearch.com';
$string['plagiarismsearch:statuslinks'] = 'Możliwość kontroli statusu raportu';
$string['plagiarismsearch:isstudent'] = 'Pozwól tylko studentom';
$string['privacy:metadata:plagiarism_ps_reports'] = 'Informacje o raportach dotyczących plagiatu.';
$string['privacy:metadata:plagiarism_ps_reports:userid'] = 'ID użytkownika powiązanego z raportem.';
$string['privacy:metadata:plagiarism_ps_reports:senderid'] = 'ID nadawcy raportu.';
$string['privacy:metadata:plagiarism_ps_reports:rid'] = 'Zdalne ID raportu dotyczącego plagiatu.';
$string['privacy:metadata:plagiarism_ps_reports:rfileid'] = 'Zdalne ID pliku powiązanego z raportem.';
$string['privacy:metadata:plagiarism_ps_reports:rserverurl'] = 'Zdalny URL serwera dla raportu dotyczącego plagiatu.';
$string['privacy:metadata:plagiarism_ps_reports:rkey'] = 'Zdalny klucz powiązany z raportem dotyczącym plagiatu.';
$string['privacy:metadata:plagiarism_ps_reports:plagiarism'] = 'Wskazuje status plagiatu w raporcie.';
$string['privacy:metadata:plagiarism_ps_reports:ai_rate'] = 'Ocena AI powiązana z raportem.';
$string['privacy:metadata:plagiarism_ps_reports:ai_probability'] = 'Prawdopodobieństwo plagiatu wykrytego przez AI.';
$string['privacy:metadata:plagiarism_ps_reports:status'] = 'Status raportu dotyczącego plagiatu.';
$string['privacy:metadata:plagiarism_ps_reports:url'] = 'URL powiązany z raportem dotyczącym plagiatu.';
$string['privacy:metadata:plagiarism_ps_reports:cmid'] = 'ID modułu kursu powiązanego z raportem.';
$string['privacy:metadata:plagiarism_ps_reports:filehash'] = 'Hash pliku powiązanego z raportem.';
$string['privacy:metadata:plagiarism_ps_reports:filename'] = 'Nazwa pliku powiązanego z raportem.';
$string['privacy:metadata:plagiarism_ps_reports:fileid'] = 'ID pliku w raporcie dotyczącym plagiatu.';
$string['privacy:metadata:plagiarism_ps_reports:log'] = 'Dane dziennika powiązane z raportem dotyczącym plagiatu.';
$string['privacy:metadata:plagiarism_ps_reports:created_at'] = 'Data i godzina utworzenia raportu.';
$string['privacy:metadata:plagiarism_ps_reports:modified_at'] = 'Data i godzina ostatniej modyfikacji raportu.';

$string['privacy:metadata:plagiarism_plagiarismsearch_client'] = 'Informacje wysyłane do klienta wyszukiwania plagiatu.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:userid'] = 'ID użytkownika wysyłającego dane.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:cmid'] = 'ID modułu kursu dla danych.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:onlinetext'] = 'Tekst online przesłany do sprawdzenia plagiatu.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:fileid'] = 'ID pliku przesłanego do sprawdzenia plagiatu.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:fileauthor'] = 'Autor pliku przesłanego do sprawdzenia plagiatu.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:filename'] = 'Nazwa pliku przesłanego do sprawdzenia plagiatu.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:file'] = 'Treść pliku przesłanego do sprawdzenia plagiatu.';

// phpcs:enable moodle.Files.LangFilesOrdering.IncorrectOrder
