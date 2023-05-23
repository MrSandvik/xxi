<?php
$XXI_matrix = array();
$florp = array();
for ($i = 0; $i < $XXI_sqlCount; $i++) {

  if ($XXI_sqlRecords[$i]['date1'] != '1900-01-01') {
    $day = 1;
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['article'] = $XXI_sqlRecords[$i]['arttext'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sortorder'] = $XXI_sqlRecords[$i]['sortorder'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day][$XXI_sqlRecords[$i]['bookref']] += $XXI_sqlRecords[$i]['adults'];
  }
  if ($XXI_sqlRecords[$i]['date2'] != '1900-01-01') {
    $day = 2;
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['article'] = $XXI_sqlRecords[$i]['arttext'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sortorder'] = $XXI_sqlRecords[$i]['sortorder'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day][$XXI_sqlRecords[$i]['bookref']] += $XXI_sqlRecords[$i]['adults'];
  }
  if ($XXI_sqlRecords[$i]['date3'] != '1900-01-01') {
    $day = 3;
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['article'] = $XXI_sqlRecords[$i]['arttext'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sortorder'] = $XXI_sqlRecords[$i]['sortorder'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day][$XXI_sqlRecords[$i]['bookref']] += $XXI_sqlRecords[$i]['adults'];
  }
  if ($XXI_sqlRecords[$i]['date4'] != '1900-01-01') {
    $day = 4;
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['article'] = $XXI_sqlRecords[$i]['arttext'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sortorder'] = $XXI_sqlRecords[$i]['sortorder'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day][$XXI_sqlRecords[$i]['bookref']] += $XXI_sqlRecords[$i]['adults'];
  }
  if ($XXI_sqlRecords[$i]['date5'] != '1900-01-01') {
    $day = 5;
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['article'] = $XXI_sqlRecords[$i]['arttext'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sortorder'] = $XXI_sqlRecords[$i]['sortorder'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day][$XXI_sqlRecords[$i]['bookref']] += $XXI_sqlRecords[$i]['adults'];
  }
  if ($XXI_sqlRecords[$i]['date6'] != '1900-01-01') {
    $day = 6;
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['article'] = $XXI_sqlRecords[$i]['arttext'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sortorder'] = $XXI_sqlRecords[$i]['sortorder'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day][$XXI_sqlRecords[$i]['bookref']] += $XXI_sqlRecords[$i]['adults'];
  }
  if ($XXI_sqlRecords[$i]['date7'] != '1900-01-01') {
    $day = 7;
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['article'] = $XXI_sqlRecords[$i]['arttext'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sortorder'] = $XXI_sqlRecords[$i]['sortorder'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day][$XXI_sqlRecords[$i]['bookref']] += $XXI_sqlRecords[$i]['adults'];
  }
  if ($XXI_sqlRecords[$i]['date8'] != '1900-01-01') {
    $day = 8;
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['article'] = $XXI_sqlRecords[$i]['arttext'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sortorder'] = $XXI_sqlRecords[$i]['sortorder'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day][$XXI_sqlRecords[$i]['bookref']] += $XXI_sqlRecords[$i]['adults'];
  }
  if ($XXI_sqlRecords[$i]['date9'] != '1900-01-01') {
    $day = 9;
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['article'] = $XXI_sqlRecords[$i]['arttext'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sortorder'] = $XXI_sqlRecords[$i]['sortorder'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day][$XXI_sqlRecords[$i]['bookref']] += $XXI_sqlRecords[$i]['adults'];
  }
  if ($XXI_sqlRecords[$i]['date10'] != '1900-01-01') {
    $day = 10;
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['article'] = $XXI_sqlRecords[$i]['arttext'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sortorder'] = $XXI_sqlRecords[$i]['sortorder'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day][$XXI_sqlRecords[$i]['bookref']] += $XXI_sqlRecords[$i]['adults'];
  }
  if ($XXI_sqlRecords[$i]['date11'] != '1900-01-01') {
    $day = 11;
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['article'] = $XXI_sqlRecords[$i]['arttext'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sortorder'] = $XXI_sqlRecords[$i]['sortorder'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day][$XXI_sqlRecords[$i]['bookref']] += $XXI_sqlRecords[$i]['adults'];
  }
  if ($XXI_sqlRecords[$i]['date12'] != '1900-01-01') {
    $day = 12;
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['article'] = $XXI_sqlRecords[$i]['arttext'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sortorder'] = $XXI_sqlRecords[$i]['sortorder'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day][$XXI_sqlRecords[$i]['bookref']] += $XXI_sqlRecords[$i]['adults'];
  }
  if ($XXI_sqlRecords[$i]['date13'] != '1900-01-01') {
    $day = 13;
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['article'] = $XXI_sqlRecords[$i]['arttext'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sortorder'] = $XXI_sqlRecords[$i]['sortorder'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day][$XXI_sqlRecords[$i]['bookref']] += $XXI_sqlRecords[$i]['adults'];
  }
  if ($XXI_sqlRecords[$i]['date14'] != '1900-01-01') {
    $day = 14;
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['article'] = $XXI_sqlRecords[$i]['arttext'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sortorder'] = $XXI_sqlRecords[$i]['sortorder'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day][$XXI_sqlRecords[$i]['bookref']] += $XXI_sqlRecords[$i]['adults'];
  }

  /*
    if ($XXI_sqlRecords[$i]['date1'] != '1900-01-01') {
    $day = 1;
    } elseif ($XXI_sqlRecords[$i]['date2'] != '1900-01-01') {
    $day = 2;
    } elseif ($XXI_sqlRecords[$i]['date3'] != '1900-01-01') {
    $day = 3;
    } elseif ($XXI_sqlRecords[$i]['date4'] != '1900-01-01') {
    $day = 4;
    } elseif ($XXI_sqlRecords[$i]['date5'] != '1900-01-01') {
    $day = 5;
    } elseif ($XXI_sqlRecords[$i]['date6'] != '1900-01-01') {
    $day = 6;
    } elseif ($XXI_sqlRecords[$i]['date7'] != '1900-01-01') {
    $day = 7;
    } elseif ($XXI_sqlRecords[$i]['date8'] != '1900-01-01') {
    $day = 8;
    } elseif ($XXI_sqlRecords[$i]['date9'] != '1900-01-01') {
    $day = 9;
    } elseif ($XXI_sqlRecords[$i]['date10'] != '1900-01-01') {
    $day = 10;
    } elseif ($XXI_sqlRecords[$i]['date11'] != '1900-01-01') {
    $day = 11;
    } elseif ($XXI_sqlRecords[$i]['date12'] != '1900-01-01') {
    $day = 12;
    } elseif ($XXI_sqlRecords[$i]['date13'] != '1900-01-01') {
    $day = 13;
    } elseif ($XXI_sqlRecords[$i]['date14'] != '1900-01-01') {
    $day = 14;
    } else {
    continue;
    }

    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['article'] = $XXI_sqlRecords[$i]['arttext'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sortorder'] = $XXI_sqlRecords[$i]['sortorder'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']]['sum']['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['adults'] += $XXI_sqlRecords[$i]['adults'];
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day]['children'] += ($XXI_sqlRecords[$i]['children1'] + $XXI_sqlRecords[$i]['children2'] + $XXI_sqlRecords[$i]['children3'] + $XXI_sqlRecords[$i]['children4']);
    $XXI_matrix[$XXI_sqlRecords[$i]['sortorder'] . '¤' . $XXI_sqlRecords[$i]['arttext']][$day][$XXI_sqlRecords[$i]['bookref']] += $XXI_sqlRecords[$i]['adults'];
   */
} # for ($i = 0; $i < $XXI_sqlCount; $i++)

ksort($XXI_matrix);
?> 

<table class="table table-striped table-sm" id="sortTable">
    <thead>
        <tr>
            <th scope="col">Total</th>
            <th scope="col border-end">Activity</th>

<?php
for ($add = 0; $add < 14; $add++) {
  ?>
              <th class="fs-8 fw-light" scope="col"><?php echo date('Y-m-d', strtotime($XXI_ymdDate . " +$add days")); ?></th>
              <?php
            } # for ($add = 0; $add <= 14; $add++)
            ?>
        </tr>
    </thead>
    <tbody>

<?php
foreach ($XXI_matrix as $entry) {
  ?>
          <tr>
              <!-- Sum guests -->
              <td>
                  <span class="fs-5">
  <?php echo $entry['sum']['adults']; ?> (<?php echo $entry['sum']['children']; ?>)
                  </span><br>
                  <span class="fs-7 text-muted">
                      0 (0)
                  </span>
              </td>

              <!-- Article name -->
              <td class="align-middle fs-5 fw-bold" scope="row" style="border-right: 1px; border-style: solid;">
  <?php echo $entry['article']; ?>
              </td>

              <!-- Count adults (children) per day -->
  <?php
  for ($day = 1; $day <= 14; $day++) {
    if ($entry[$day]['adults'] + $entry[$day]['children'] == 0) {
      $style1 = "fs-6 text-muted";
      $link1 = "";
    } else {
      $style1 = "fs-6 text-success fw-bold";
    }
    ?>

                <td>
                    <span style="padding-left: 10px" class="<?php echo $style1; ?>">
    <?php echo 0 + $entry[$day]['adults']; ?> (<?php echo 0 + $entry[$day]['children']; ?>)
                    </span><br>
                    <span style="padding-left: 10px" class="fs-6 text-muted"">
                        0 (0)
                    </span>
                </td>

    <?php
  } #  for ($day = 1; $day < 14 ; $day++)
  ?>

          </tr>

  <?php
} # foreach($XXI_matrix as $entry)
?>



    </tbody>
</table>
<hr>
<pre>
<?php
print_r($XXI_matrix);
