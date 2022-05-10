<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use function GuzzleHttp\Psr7\uri_for;


class TimetableController extends Controller
{

    /**
     * @param $name - текст поиска. Например, 585
     * @param $type - тип поиска students/lecturers
     *
     * Возвращает json с именем группы/преподавателя и со ссылкой на расписание
     */
    public function searchTimetable($name, $type) {
        $link = "https://www.asu.ru/timetable/search/$type/?query=$name";
        $html = file_get_contents($link);

        $crawler = new Crawler($html);
        $crawler = $crawler->filter('.grid' . '.margin_bottom_x')->first();

        $items = $crawler
            ->filter('.margin_bottom')
            ->each(function($node){
                $href = $node->children()->attr('href');
                $text = $node->text();
                return compact('text', 'href');
            });
        return json_encode($items, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param $query - запрос с учётом недели
     * @param $type - тип поиска
     * @param $numGroup - номер группы
     *
     * Возвращает данные о расписании в виде json
     */
    public function getTimetable($query, $type, $numGroup = "1"){
        $loc        = "";
        $time       = "";
        $date       = "";
        $para       = "";
        $teacher    = "";
        $secondTime = "";

        $link = "https://www.asu.ru$query";
        $html = file_get_contents($link);

        $crawler = new Crawler($html);
        if ($type === "students")
            $crawler = $crawler->filter('.align_top' . '.schedule_table')->first();
        else
            $crawler = $crawler->filter('.schedule_table' . '.align_top')->first();

        $items = $crawler
            ->children()
            ->each(function($node) use (&$loc, &$time, &$date, &$para, &$teacher, &$secondTime, $numGroup){
            $class = $node->attr('class');
            switch ($class){
                case "schedule_table-date":
                    $loc        = "";
                    $time       = "";
                    $para       = "";
                    $teacher    = "";
                    $date = $node->text();
                    break;
                case "schedule_table-time schedule_table-current":
                    $timePar = $node->children()->eq(1)->text();
                    $timeSplit = explode(':', $timePar);
                    if(count($timeSplit) > 1){
                        $secondTime = $timePar;
                        $time = $secondTime;
                    } else {
                        $time = $secondTime;
                    }
                    $para = $node->children()->eq(2)->text();
                    $teacher = $node->children()->eq(3)->text();
                    if ($teacher === "") $teacher = " ";
                    $loc = $node->children()->eq(4)->text();
                    break;
                case "schedule_table-time":
                    $timePar = $node->children()->eq(1)->text();
                    $timeSplit = explode(':', $timePar);
                    if(count($timeSplit) > 1){
                        $secondTime = $timePar;
                        $time = $secondTime;
                    } else {
                        $time = $secondTime;
                    }
                    $para = $node->children()->eq(2)->text();
                    $teacher = $node->children()->eq(3)->text();
                    if ($teacher === "") $teacher = " ";
                    $loc = $node->children()->eq(4)->text();
                    break;
            }
            return compact('time', 'teacher', 'loc', 'date', 'para', 'numGroup');
        });

        return json_encode($items, JSON_UNESCAPED_UNICODE);
    }
}
