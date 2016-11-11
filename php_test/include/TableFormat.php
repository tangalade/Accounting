<?php

class TableFormat {

    public static function accounts_json_to_inner_html_table ($json){
        $html = "<thead>\n<tr>\n";
        foreach ($json as $head => $tmp) {
            $num_rows = count($json[$head]);
            $html .= "<th>" . $head . "</th>\n";
        }
        $html .= "</tr>\n</thead>\n";
        $html .= "<tbody>\n";
        if ($num_rows > 0) {
            for ($row_num=0; $row_num<$num_rows; $row_num++) {
                $html .= "<tr>\n";
                foreach ($json as $head => $tmp) {
                    $html .= "<td>" . $tmp[$row_num] . "</td>\n";
                }
                $html .= "</tr>\n";
            }
        }
        $html .= "</tbody>\n";
        $html .= "<tfoot>\n<tr>\n";
        $html .= "<td></td>";
        foreach ($json as $head => $tmp) {
            $html .= "<th>" . $head . "</th>\n";
        }
        $html .= "</tr>\n</tfoot>\n";
        return $html;
    }

    public static function banks_json_to_inner_html_table($json){
        $html = "<thead>\n<tr>\n";
        // details-control col
        $html .= "<th></th>";
        foreach ($json as $head => $tmp) {
            $num_rows = count($json[$head]);
            $html .= "<th>" . $head . "</th>\n";
        }
        $html .= "</tr>\n</thead>\n";
        $html .= "<tbody>\n";
        if ($num_rows > 0) {
            for ($row_num=0; $row_num<$num_rows; $row_num++) {
                $html .= "<tr>\n";
                $html .= "<td></td>";
                foreach ($json as $head => $tmp) {
                    $html .= "<td>" . $tmp[$row_num] . "</td>\n";
                }
                $html .= "</tr>\n";
            }
        }
        $html .= "</tbody>\n";
        return $html;
    }

}
