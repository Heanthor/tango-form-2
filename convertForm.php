<?php
    $classes = array(2 => "Finding sacadas",
                    3 => "Float like a butterfly",
                    5 => "Shell Milonga",
                    8 => "Impossibly small turns",
                    9 => "Dance between the beats",
                    11 => "Rhythmic alteraciones",
                    12 => "Axis unhinged",
                    14 => "Saturday Yoga",
                    16 => "Floating walk",
                    17 => "Uncovering the magic: musicality",
                    19 => "Tersichore's milonga",
                    22 => "Silky embrace",
                    23 => "Wax on wax off",
                    25 => "Making colgadas effortless",
                    26 => "Rapid fire footwork",
                    28 => "Sunday Yoga",
                    30 => "Ganchos and wraps",
                    31 => "Finding your power",
                    33 => "Shellebration milonga");

    function get_classname($index) {
        global $classes;
        if (!in_array($index, array_keys($classes))) {
            return "INVALID INDEX";
        } else {
            return $classes[$index];
        }
    }
?>