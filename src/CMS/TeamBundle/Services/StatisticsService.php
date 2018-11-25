<?php
namespace CMS\TeamBundle\Services;

class StatisticsService
{
    public function getFormattedDataForNameQuantityChart($data) {
        $result["labels"] = [];
        $result["data"] = [];

        foreach($data as $item) {
            if ($item['name'] == '0') {
                $result["labels"][] = "Not paid";
            } elseif ($item['name'] == '1') {
                $result["labels"][] = "Paid";
            } else {
                $result["labels"][] = $item['name'];
            }
            $result["data"][] = $item['quantity'];
        }

        return $result;
    }

}