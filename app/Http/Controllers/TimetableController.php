<?php

namespace App\Http\Controllers;

use App\Models\Search;
use App\Models\Week;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use function GuzzleHttp\Psr7\uri_for;
use function Symfony\Component\String\s;


class TimetableController extends Controller
{

    public function index() {
        return Search::all();
    }

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
            ->each(function ($node) {
                $link    = $node->children()->attr('href');
                $name    = $node->text();
                return compact('name', 'link');
            });

        foreach ($items as $item) {
            $current = Search::where('name', $item['name'])->first();
            if (empty($current)) {
                Search::create([
                    'name' => $item['name'],
                    'link' => $item['link'],
                ]);
            } else {
                $current->link = $item['link'];
                $current->save();
            }
        }

        return json_encode($items, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param $query - запрос с учётом недели
     * @param $type - тип поиска
     * @param $numGroup - номер группы
     *
     * Возвращает данные о расписании в виде json
     */
    public function getTimetable($type, $numGroup = "1", $weekNum = 1, $date1 = "20220502", $date2 = "20220508"){
        $loc        = "";
        $time       = "";
        $date       = "";
        $para       = "";
        $teacher    = "";
        $secondTime = "";

        $searchModel = Search::where('name', $numGroup)->first();
        $query = $searchModel->link;

        $link = "https://www.asu.ru$query?date=$date1-$date2";
        $html = file_get_contents($link);
        $crawler = new Crawler($html);
        if ($type === "students")
            $crawler = $crawler->filter('.align_top' . '.schedule_table')->first();
        else
            $crawler = $crawler->filter('.schedule_table' . '.align_top')->first();

        $items = $crawler
            ->children()
            ->each(function($node) use (&$loc, &$time, &$date, &$para, &$teacher, &$secondTime, $numGroup, $weekNum){
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
            return compact('weekNum', 'time', 'teacher', 'loc', 'date', 'para', 'numGroup');
        });

        $current = Week::where('weekNum', $weekNum)->first();
        if (empty($current)){
            Week::create([
                'groupNum' => $numGroup,
                'weekNum' => $weekNum,
                'timetable' => json_encode($items, JSON_UNESCAPED_UNICODE)
            ]);
        } else {
            $current->timetable = json_encode($items, JSON_UNESCAPED_UNICODE);
            $current->save();
        }

        return Week::where(['groupNum' => $numGroup, 'weekNum' => $weekNum])->get();
    }
}
