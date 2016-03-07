<?php
    // DATA
    // map table indices to events
    $milongas = array(5, 19, 33);
    $yogas = array(14, 28);
    // start, end ranges for each day
    $day1 = array("min" => 2, "max" => 3);
    $day2 = array("min" => 8, "max" => 17);
    $day3 = array("min" => 22, "max" => 31);
    // price toggle
    $early_registration = true;

    // all prices (general, partner (general), student, partner (student)
    if ($early_registration) {
        // EARLY PRICES
        $single_workshops = array(27, 54, 18, 36);
        $friday_milongas = array(16, 32, 10, 20);
        $saturday_milongas = array(18, 36, 12, 24);
        $sunday_milongas = array(15, 30, 8, 16);
        $milonga_pass = array(45, 90, 25, 50);
        $friday_pass = array(36, 66, 20, 40);
        $saturday_pass = array(82, 148, 30, 60);
        $sunday_pass = array(78, 140, 25, 50);
        $yoga = array(10, 20, 5, 10);
        $milonga_pass_discount = array(4, 8, 5, 10);
        $full_pass = array(170, 310, 60, 120);
    } else {
        // LATE PRICES
        $single_workshops = array(27, 54, 18, 36);
        $friday_milongas = array(16, 32, 10, 20);
        $saturday_milongas = array(18, 36, 12, 24);
        $sunday_milongas = array(15, 30, 8, 16);
        $milonga_pass = array(45, 90, 25, 50);
        $friday_pass = array(36, 66, 20, 40);
        $saturday_pass = array(82, 148, 30, 60);
        $sunday_pass = array(78, 140, 25, 50);
        $yoga = array(10, 20, 5, 10);
        $milonga_pass_discount = array(4, 8, 5, 10);
        $full_pass = array(170, 310, 60, 120);
    }
    $all_prices = array("single_workshops" => $single_workshops,

                        "friday_milonga" => $friday_milongas,
                        "saturday_milonga" => $saturday_milongas,
                        "sunday_milonga" => $sunday_milongas,

                        "milongas_pass" => $milonga_pass,
                        "friday_pass" => $friday_pass,
                        "saturday_pass" => $saturday_pass,
                        "sunday_pass" => $sunday_pass,

                        "yoga" => $yoga,
                        "milonga_pass_discount" => $milonga_pass_discount,
                        "full_pass" => $full_pass);

    function get_prices_for_pass_type($pass_type) {
        $toReturn = array();
        global $all_prices;

        $map = array("General" => 0, "General (partner)"=> 1,
        "Student" => 2, "Student (partner)" => 3);

        foreach($all_prices as $name => $price_array) {
            $toReturn[$name] = $price_array[$map[$pass_type]];
        }

        return $toReturn;
    }

    function inside_range($number, $min, $max) {
        //inclusive
        return ($min <= $number) && ($number <= $max);
    }

    function get_outlier($dict) {
        $totals = array_values($dict);
        $avg = array_sum($totals) / count($totals);
        $outliers = array();

        foreach ($dict as $day => $total) {
            if ($total >= $avg * 1.5) {
                array_push($outliers, $day);
            }
        }

        return $outliers;
    }

    // Runnable script
    function main() {
        global $day1;
        global $day2;
        global $day3;
        global $milongas;
        global $yogas;

        //echo $_GET['selection']; //temp
        $selections = json_decode($_GET['selection']);
        $passtype_in = $_GET['passtype'];

        if ($passtype_in == -1) {
            echo json_encode(array("No pass type selected", 0));
            return;
        }
        $user_prices = get_prices_for_pass_type($passtype_in);
        /* LOGIC:
            Keep running total for each day, and for milongas
            If one total is much greater than the others, select that pass
            If more than one total is greater, select cheaper of two passes or full pass
            If selections are sporadic, choose ala carte
        */
        $day1_total = 0;
        $day2_total = 0;
        $day3_total = 0;

        $day1_count = 0;
        $day2_count = 0;
        $day3_count = 0;

        $milonga_total = 0;
        $user_single_workshop_price = $user_prices["single_workshops"];
        $yoga_price = $user_prices["yoga"];

        //process selection
        foreach ($selections as $selection) {
            // sum as all single workshops (ala carte) first
            if (inside_range($selection, $day1["min"], $day1["max"])) {
                $day1_total += $user_single_workshop_price;

                $day1_count++;
            } else if (inside_range($selection, $day2["min"], $day2["max"])) {
                // Check if yoga is selected, different price
                if ($selection == $yogas[0]) {
                    $day2_total += $yoga_price;
                } else {
                    $day2_total += $user_single_workshop_price;
                }

                $day2_count++;
            } else if (inside_range($selection, $day3["min"], $day3["max"])) {
                if ($selection == $yogas[1]) {
                    $day3_total += $yoga_price;
                } else {
                    $day3_total += $user_single_workshop_price;
                }
                $day3_count++;
            } else if (in_array($selection, $milongas)) {
                if ($selection == $milongas[0]) {
                    // day 1 milonga
                    $milonga_total += $user_prices["friday_milonga"];
                    $day1_total += $user_prices["friday_milonga"];
                } else if ($selection ==  $milongas[1]) {
                    // day 2 milonga
                    $milonga_total += $user_prices["saturday_milonga"];
                    $day2_total += $user_prices["saturday_milonga"];
                } else {
                    // day 3 milonga
                    $milonga_total += $user_prices["sunday_milonga"];
                    $day3_total += $user_prices["sunday_milonga"];
                }
            } else {
                echo("Invalid selection");
            }
        }
        // Now, assess totals
        $totals = array("Friday" => $day1_total,
                        "Saturday" => $day2_total,
                        "Sunday" => $day3_total,
                        "Milonga" => $milonga_total);
        $total_alacarte_cost = array_sum(array_values($totals));
        //echo $total_alacarte_cost." ";

        $cheapest_total = 0;

        // find cheapest option for each pass category
        if ($day1_total < $user_prices["friday_pass"]) {
            $cheapest_day1 = "alacarte";
            $cheapest_total += $day1_total;
        } else {
            $cheapest_day1 = "pass";
            $cheapest_total += $user_prices["friday_pass"];
        }

        if ($day2_total < $user_prices["saturday_pass"]) {
            $cheapest_day2 = "alacarte";
            $cheapest_total += $day2_total;
        } else {
            $cheapest_day2 = "pass";
            $cheapest_total += $user_prices["saturday_pass"];
        }

        if ($day3_total < $user_prices["sunday_pass"]) {
            $cheapest_day3 = "alacarte";
            $cheapest_total += $day3_total;
        } else {
            $cheapest_day3 = "pass";
            $cheapest_total += $user_prices["sunday_pass"];
        }

        if ($milonga_total < $user_prices["milongas_pass"]) {
            $cheapest_milonga = "alacarte";
        } else {
            $cheapest_milonga = "pass";
        }

        // weigh cheapest options vs full pass
        $recommendation = array();
        if ($cheapest_total > $user_prices["full_pass"]) {
            array_push($recommendation, "Full pass");
            $cheapest_total = $user_prices["full_pass"];
        } else {
            // find multiple pass combinations
            if ($cheapest_day1 == "pass") {
                array_push($recommendation, "Friday Pass");
            } else if ($day1_total != 0) {
                array_push($recommendation, "A la carte");
            }

            if ($cheapest_day2 == "pass") {
                array_push($recommendation, "Saturday Pass");
            } else if ($day2_total != 0) {
                array_push($recommendation, "A la carte");
            }

            if ($cheapest_day3 == "pass") {
                array_push($recommendation, "Sunday Pass");
            } else if ($day3_total != 0) {
                array_push($recommendation, "A la carte");
            }
        }

        // custom clause for milonga pass
        if (in_array($milongas[0], $selections) &&
            in_array($milongas[1], $selections) &&
            in_array($milongas[2], $selections) &&
            $day2_count < 3 &&
            $day3_count < 3 &&
            $day1_count + $day2_count + $day3_count < 4 &&
            $cheapest_day1 != "pass" &&
            $cheapest_day2 != "pass" &&
            $cheapest_day3 != "pass") {
                array_push($recommendation, "Milonga pass");
                $cheapest_total -= $user_prices['milonga_pass_discount'];
        }

        if (in_array($milongas[0], $selections) &&
            in_array($milongas[1], $selections) &&
            in_array($milongas[2], $selections) &&
            count($selections) == 3) {
                $recommendation = array("Milonga pass");
        }

        $recommendation = array_unique($recommendation);

        $payload = array();
        if (count($recommendation) > 0) {
            array_push($payload, implode(", ", $recommendation));
        } else {
            array_push($payload, "A la carte");
        }

        array_push($payload, $cheapest_total);
        echo json_encode($payload);
    }
    main();
?>
