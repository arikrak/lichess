<?php

namespace Lichess\ChartBundle\Chart;

use Bundle\LichessBundle\Document\History;

class UserEloChart
{
    /**
     * Elo history
     *
     * @var History
     */
    protected $history;

    /**
     * Maximum number of data points
     *
     * @var int
     */
    protected $points = 100;
    //protected $points = 230;

    protected $median = 15;

    public function __construct(History $history)
    {
        $this->history = $history;
    }

    public function hasData()
    {
        return $this->history->size() > 1;
    }

    public function getColumns()
    {
        return array(
            array('string', 'Game'),
            array('number', 'Elo'),
            array('number', 'Median')
        );
    }

    public function getRows()
    {
        $elos = $this->history->getEloByTs();
        $elos = $this->reduce($elos);
        $elosAndMedian = $this->addMedian($elos);

        $data = array();
        foreach ($elosAndMedian as $ts => $eloAndMed) {
            $date = date('M j', $ts);
            $data[] = array($date, $eloAndMed[1], $eloAndMed[0]);
        }

        return $data;
    }

    protected function addMedian(array $elos)
    {
      $cur = reset($elos);
      $indexedElos = array_values($elos);
      $ar = array();
      $it = 0;
      foreach ($elos as $ts => $val) {
        $since = max(0, $it - $this->median);
        $length = $this->median + min($it, $this->median);
        $slice = array_slice($indexedElos, $since, $length);
        $median = array_sum($slice) / count($slice);
        $ar[$ts] = array($val, (int) $median);
        $it++;
      }

      return $ar;
    }

    protected function reduce(array $elos)
    {
        $count = count($elos);
        if ($count <= $this->points) {
            return $elos;
        }

        $ts = array_keys($elos);
        $es = array_values($elos);
        $reduced = array();
        $factor = $count/$this->points;
        for ($i = 0; $i < $this->points; $i++) {
            $key = round($i*$factor);
            $reduced[$ts[$key]] = $es[$key];
        }
        // prevents chart lag: add last data point to the end
        if (end($reduced) != end($es)) {
            $reduced[end($ts)] = end($es);
        }

        return $reduced;
    }
}
