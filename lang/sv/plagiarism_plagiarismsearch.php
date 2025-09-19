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
 * Spanish language pack
 *
 * @package    plagiarism_plagiarismsearch
 * @author     Alex Crosby developer@plagiarismsearch.com
 * @copyright  @2018 PlagiarismSearch.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// phpcs:disable moodle.Files.LangFilesOrdering.IncorrectOrder
$string['plagiarismsearch'] = 'PlagiarismSearch';
$string['pluginname'] = 'PlagiarismSearch';

$string['auto_check'] = 'Automatisk kontroll';
$string['manual_check'] = 'Manuell kontroll';
$string['add_to_storage'] = 'Lägg till i lagring';

$string['detect_ai'] = 'Upptäck AI';
$string['sources_type'] = 'Källor';
$string['sources_doc_web_storage'] = 'Dokument vs Web + Lagring';
$string['sources_doc_web'] = 'Dokument vs Web';
$string['sources_doc_storage'] = 'Dokument vs Lagring';

$string['report_type'] = 'Rapportfiltyp';
$string['report_show_no'] = 'Visa inte';
$string['report_show_pdf'] = 'Endast PDF-rapport';
$string['report_show_html'] = 'Endast HTML-rapport';
$string['report_show_pdf_html'] = 'PDF- och HTML-rapporter';

$string['report_language'] = 'Rapportspråk';
$string['report_language_default'] = 'Standard (engelska)';
$string['report_language_en'] = 'Engelska';
$string['report_language_es'] = 'Spanska';
$string['report_language_ua'] = 'Ukrainska';
$string['report_language_pl'] = 'Polska';
$string['report_language_ru'] = 'Ryska';

$string['student_show_percentage'] = 'Tillåt studenter att se plagieringsprocent';
$string['student_show_reports'] = 'Tillåt studenter att se rapporter';
$string['student_submit'] = 'Tillåt studenter att lämna in arbeten';
$string['student_resubmit'] = 'Tillåt studenter att lämna in på nytt';
$string['student_resubmit_numbers'] = 'Antal om-inlämningar';
$string['student_disclosure'] = 'Studentinformation';
$string['student_disclosure_default'] = 'Alla uppladdade filer kommer att skickas till PlagiarismSearch.coms kontrolltjänst.';
$string['student_error_nopermission'] = 'Ingen behörighet';

$string['api_url'] = 'API-url';
$string['api_key'] = 'API-nyckel';
$string['api_user'] = 'API-användare';
$string['api_debug'] = 'API-felsökning';
$string['api_version'] = 'API-version';
$string['filter_chars'] = 'Endast latinska tecken';
$string['filter_references'] = 'Uteslut referenser';
$string['filter_quotes'] = 'Uteslut textcitat';

$string['filter_plagiarism'] = 'Uteslut självplagiat';
$string['filter_plagiarism_no'] = 'Nej (rekommenderas inte)';
$string['filter_plagiarism_user_course'] = 'Uteslut användarplagiat inom samma kurs';
$string['filter_plagiarism_user'] = 'Uteslut användarplagiat';
$string['filter_plagiarism_course'] = 'Uteslut kursplagiat (rekommenderas inte)';

$string['parse_text_url'] = 'Tillåt URL-tolkning i text';
$string['valid_parsed_text_url'] = 'Giltiga URL-listor för tolkning';
$string['plagiarismsearch:isadministrator'] = 'Tillåt endast administratörer';

$string['submit'] = 'Skicka till PlagiarismSearch';
$string['resubmit'] = 'Skicka om till PlagiarismSearch';
$string['processing'] = 'Pågår';
$string['unknown_error'] = 'Okänt fel';

$string['pdf_report'] = 'Ladda ner PDF-rapport';
$string['html_report'] = 'Visa HTML-rapport';
$string['review_report'] = 'Granska rapport';
$string['show_review_link'] = 'Tillåt lärare att granska rapporter';
$string['link_title'] = 'PlagiarismSearch.com – avancerad online-plagiatkontroll';
$string['check_status'] = 'Kontrollera status';
$string['temp_folder_not_exists'] = 'Tillfällig mapp finns inte';
$string['server_connection_error'] = 'Problem med anslutning till PlagiarismSearch-servern';
$string['submit_ok'] = 'Dokumentet \'{$a}\' har skickats till PlagiarismSearch';
$string['submit_error'] = 'Dokumentet \'{$a}\' skickades inte';
$string['submit_onlinetext_ok'] = 'Onlinetext skickades till PlagiarismSearch';
$string['submit_onlinetext_error'] = 'Onlinetext skickades inte';
$string['status_ok'] = 'Dokument';
$string['status_error'] = 'Fel. Dokument';
$string['status'] = 'status';
$string['is_in'] = 'finns i';
$string['plagiarism'] = 'Plagiat';
$string['ai'] = 'AI';
$string['ai_rate'] = 'Total AI-nivå';
$string['ai_probability'] = 'AI-sannolikhet';
$string['empty_parameter'] = 'Tom parameter \'{$a}\'';
$string['report_not_found'] = 'Rapporten hittades inte';
$string['no_cmid_or_id'] = 'Ingen cmid eller id';
$string['api_error'] = 'PlagiarismSearch API-fel';

$string['report'] = 'Rapport';
$string['save'] = 'Spara';
$string['settings_error'] = 'Ett fel uppstod vid uppdatering av PlagiarismSearch-inställningarna';
$string['settings_error_server'] = 'PlagiarismSearch-inställningar API-fel';
$string['settings_saved'] = 'PlagiarismSearch-inställningar har sparats framgångsrikt';
$string['use'] = 'Aktivera PlagiarismSearch';
$string['enabled'] = 'Aktivera PlagiarismSearch';
$string['yellow'] = 'Plagiatnivå gul börjar vid';
$string['red'] = 'Plagiatnivå röd börjar vid';

$string['text_plain'] = 'PlagiarismSearch.com – avancerad online-plagiatkontroll. <br/>
PlagiarismSearch.com är en ledande plagiatkontrollwebbplats som ger dig en noggrann rapport inom kort tid. <br/>
Ta reda på hur du registrerar dig <a href="https://plagiarismsearch.com/account/signup">här</a> och be oss om en gratis provperiod <a href="mailto:support@plagiarismsearch.com">support@plagiarismsearch.com</a><br/><br/>';

$string['plagiarismsearch:viewlinks'] = 'Möjlighet att visa länkar för plagiatresultat';
$string['plagiarismsearch:submitlinks'] = 'Möjlighet att skicka länkar till PlagiarismSearch.com';
$string['plagiarismsearch:statuslinks'] = 'Möjlighet att kontrollera rapportstatus';
$string['plagiarismsearch:isstudent'] = 'Tillåt endast studenter';
$string['only_admin_can_configure_course'] = 'Endast administratörer kan konfigurera kursinställningar';

$string['privacy:metadata:plagiarism_ps_reports'] = 'Information om plagiatrapporter.';
$string['privacy:metadata:plagiarism_ps_reports:userid'] = 'ID för användaren som är kopplad till rapporten.';
$string['privacy:metadata:plagiarism_ps_reports:senderid'] = 'ID för avsändaren av rapporten.';
$string['privacy:metadata:plagiarism_ps_reports:rid'] = 'Det externa ID:t för plagiatrapporten.';
$string['privacy:metadata:plagiarism_ps_reports:rfileid'] = 'Det externa ID:t för filen som är kopplad till rapporten.';
$string['privacy:metadata:plagiarism_ps_reports:rserverurl'] = 'Den externa server-URL:en för plagiatrapporten.';
$string['privacy:metadata:plagiarism_ps_reports:rkey'] = 'Den externa nyckeln som är kopplad till plagiatrapporten.';
$string['privacy:metadata:plagiarism_ps_reports:plagiarism'] = 'Indikerar plagiatstatus för rapporten.';
$string['privacy:metadata:plagiarism_ps_reports:ai_rate'] = 'AI-nivån som är kopplad till rapporten.';
$string['privacy:metadata:plagiarism_ps_reports:ai_probability'] = 'Sannolikheten för AI-upptäckt plagiat.';
$string['privacy:metadata:plagiarism_ps_reports:status'] = 'Statusen för plagiatrapporten.';
$string['privacy:metadata:plagiarism_ps_reports:url'] = 'URL relaterad till plagiatrapporten.';
$string['privacy:metadata:plagiarism_ps_reports:cmid'] = 'ID för kursmodulen som är kopplad till rapporten.';
$string['privacy:metadata:plagiarism_ps_reports:filehash'] = 'Hash-värdet för filen som är kopplad till rapporten.';
$string['privacy:metadata:plagiarism_ps_reports:filename'] = 'Namnet på filen som är kopplad till rapporten.';
$string['privacy:metadata:plagiarism_ps_reports:fileid'] = 'ID för filen i plagiatrapporten.';
$string['privacy:metadata:plagiarism_ps_reports:log'] = 'Loggdata relaterad till plagiatrapporten.';
$string['privacy:metadata:plagiarism_ps_reports:created_at'] = 'Datum och tid när rapporten skapades.';
$string['privacy:metadata:plagiarism_ps_reports:modified_at'] = 'Datum och tid när rapporten senast ändrades.';

$string['privacy:metadata:plagiarism_plagiarismsearch_client'] = 'Information som skickas till plagiatsökklienten.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:userid'] = 'ID för användaren som skickar data.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:cmid'] = 'ID för kursmodulen för datan.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:onlinetext'] = 'Onlinetext som skickas för plagiatkontroll.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:fileid'] = 'ID för filen som skickas för plagiatkontroll.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:fileauthor'] = 'Författaren till filen som skickas för plagiatkontroll.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:filename'] = 'Namnet på filen som skickas för plagiatkontroll.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:file'] = 'Filinnehållet som skickas för plagiatkontroll.';

// phpcs:enable moodle.Files.LangFilesOrdering.IncorrectOrder
