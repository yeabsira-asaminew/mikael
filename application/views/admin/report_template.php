<!DOCTYPE html>
<html>

<head>
    <title>ሰንበት አቴንዳንስ ሲስተም የተማሪዎች ሪፖርት</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Nyala', 'Abyssinica SIL', sans-serif;
            margin: 20px;
        }

        h2,
        h3 {
            color: #333;
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 8px;
            text-align: center;
            font-size: 14px;
        }

        th {
            background-color: #f2f2f2;
        }

        table {
            margin-bottom: 20px;
            width: 50%;
        }

        .chart-container {
            width: 80%;
            margin: 20px auto;
        }

        /* General body styling */
        .report-body {
            font-family: 'Nyala', 'Abyssinica SIL', sans-serif;
            margin: 20px;
        }

        /* Table styling */
        .report-table {
            border-collapse: collapse;
            width: 70%;
            margin-bottom: 20px;
            border: 1px solid black;
        }

        .report-table th,
        .report-table td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        .report-table th {
            background-color: #f2f2f2;
            /* Light gray background for headers */
            font-weight: bold;
        }

        /* Total row styling */
        .report-total-row {
            background-color: #ADD8E6;
            /* Light blue background */
            font-weight: bold;
        }

        /* Header styling */
        .report-header {
            color: #333;
            /* Dark gray color */
        }

        .report-section-header {
            margin-top: 20px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>





    <!-- Yearly Report -->
    <?php foreach ($reportData as $year => $counts) { ?>
        <h2 class="report-section-header">በ<?= $year ?> ዓ.ም የተመዘገቡ ተማሪዎች</h2>
        <table border="1" cellpadding="5" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ዓመት</th>
                    <th>ወንድ</th>
                    <th>ሴት</th>
                    <th>ጠቅላላ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $year ?> ዓ.ም</td>
                    <td><?= $counts['male_count'] ?? 0 ?></td>
                    <td><?= $counts['female_count'] ?? 0 ?></td>
                    <td><?= $counts['total_count'] ?? 0 ?></td>
                </tr>
            </tbody>
        </table>
        <br>
    <?php } ?>
 <!--
    <?php
    // Calculate grand totals if multiple years selected
    if (count($reportData) > 1) {
        $grandTotalMale = 0;
        $grandTotalFemale = 0;
        $grandTotal = 0;

        foreach ($reportData as $yearData) {
            $grandTotalMale += $yearData['male_count'];
            $grandTotalFemale += $yearData['female_count'];
            $grandTotal += $yearData['total_count'];
        }
    ?>
        <h2 class="report-section-header">በተመረጡት ዓመታት የተመዘገቡ ተማሪዎች ጠቅላላ</h2>
        <table border="1" cellspacing="0" cellpadding="5" width="100%">
            <tr>
                <th>ጠቅላላ ወንድ ተማሪዎች</th>
                <th>ጠቅላላ ሴት ተማሪዎች</th>
                <th>ጠቅላላ ተማሪዎች</th>
            </tr>
            <tr>
                <td><?= $grandTotalMale ?></td>
                <td><?= $grandTotalFemale ?></td>
                <td><?= $grandTotal ?></td>
            </tr>
        </table>
    <?php } ?>
    -->

    <!-- status based -->
    <?php if (is_array($selected_data) && in_array('student_status_report', $selected_data)): ?>
        <h2 class="report-section-header">የተማሪዎች ቁጥር በትምህርት ሁኔታ(active | inactive)</h2>
        <table border="1" cellpadding="5" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ሁኔታ</th>
                    <th>ወንድ</th>
                    <th>ሴት</th>
                    <th>ጠቅላላ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_male = 0;
                $total_female = 0;
                $total_all = 0;

                foreach ($student_status_report as $row):
                    $status = $row['status'] == 1 ? 'ንቁ' : 'ንቁ ያልሆነ';
                    $male = $row['male'];
                    $female = $row['female'];
                    $total = $row['total'];

                    $total_male += $male;
                    $total_female += $female;
                    $total_all += $total;
                ?>
                    <tr>
                        <td><?= $status ?></td>
                        <td><?= $male ?></td>
                        <td><?= $female ?></td>
                        <td><?= $total ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr style="font-weight: bold;">
                    <td style="font-weight: bold;">ጠቅላላ</td>
                    <td><?= $total_male ?></td>
                    <td><?= $total_female ?></td>
                    <td><?= $total_all ?></td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- age based -->
    <?php if (is_array($selected_data) && in_array('age_group_report', $selected_data)): ?>
        <h2 class="report-section-header">የተማሪዎች ቁጥር በእድሜ ክፍል</h2>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>የእድሜ ክፍል</th>
                    <th>ወንድ</th>
                    <th>ሴት</th>
                    <th>ጠቅላላ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rows = [
                    'ደቂቅ'        => ['under_7_male', 'under_7_female'],
                    'ቂርቆስ'         => ['between_7_11_male', 'between_7_11_female'],
                    'ማዕከላዊያን'        => ['between_11_16_male', 'between_11_16_female'],
                    'አዳጊ ወጣት'        => ['between_16_19_male', 'between_16_19_female'],
                    'ወጣት' => ['young_male', 'young_female'],
                    'ነባር አባል'   => ['adult_male', 'adult_female']
                ];

                foreach ($rows as $label => [$maleKey, $femaleKey]) {
                    $male = $age_group_report[$maleKey];
                    $female = $age_group_report[$femaleKey];
                    $total = $male + $female;
                    echo "<tr>
                <td>{$label}</td>
                <td>{$male}</td>
                <td>{$female}</td>
                <td>{$total}</td>
            </tr>";
                }
                ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- apostolic based -->
    <?php if (is_array($selected_data) && in_array('apostolic_category_report', $selected_data)): ?>
        <h2 class="report-section-header">የተማሪዎች ቁጥር በሐዋርያዊ ክፍል</h2>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ሐዋርያዊ ክፍል</th>
                    <th>ወንድ</th>
                    <th>ሴት</th>
                    <th>ጠቅላላ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_male = 0;
                $total_female = 0;
                $grand_total = 0;

                foreach ($apostolic_category_report as $row):
                    $apostolic_name = $row['apostolic_name'] ?? 'የሌላቸው';
                    $male = $row['male'];
                    $female = $row['female'];
                    $total = $row['total'];

                    $total_male += $male;
                    $total_female += $female;
                    $grand_total += $total;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($apostolic_name, ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= $male ?></td>
                        <td><?= $female ?></td>
                        <td><?= $total ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr style="font-weight: bold;">
                    <td style="font-weight: bold;">ጠቅላላ</td>
                    <td><?= $total_male ?></td>
                    <td><?= $total_female ?></td>
                    <td><?= $grand_total ?></td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- curriculum based -->
    <?php if (is_array($selected_data) && in_array('curriculum_category_report', $selected_data)): ?>
        <h2 class="report-section-header">የተማሪዎች ቁጥር በስርአተ ትምህርት ክፍል</h2>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ስርአተ ትምህርት ክፍል</th>
                    <th>ወንድ</th>
                    <th>ሴት</th>
                    <th>ጠቅላላ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_male = 0;
                $total_female = 0;
                $grand_total = 0;

                foreach ($curriculum_category_report as $row):
                    $curriculum_name = $row['curriculum_name'] ?? 'የሌላቸው';
                    $male = $row['male'];
                    $female = $row['female'];
                    $total = $row['total'];

                    $total_male += $male;
                    $total_female += $female;
                    $grand_total += $total;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($curriculum_name, ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= $male ?></td>
                        <td><?= $female ?></td>
                        <td><?= $total ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr style="font-weight: bold;">
                    <td style="font-weight: bold;">ጠቅላላ</td>
                    <td><?= $total_male ?></td>
                    <td><?= $total_female ?></td>
                    <td><?= $grand_total ?></td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>
    
    <!-- department based -->
    <?php if (is_array($selected_data) && in_array('department_category_report', $selected_data)): ?>
        <h2 class="report-section-header">የተማሪዎች ቁጥር በአገልግሎት ክፍል</h2>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>አገልግሎት ክፍል</th>
                    <th>ወንድ</th>
                    <th>ሴት</th>
                    <th>ጠቅላላ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_male = 0;
                $total_female = 0;
                $grand_total = 0;

                foreach ($department_category_report as $row):
                    $department_name = $row['department_name'] ?? 'የሌላቸው';
                    $male = $row['male'];
                    $female = $row['female'];
                    $total = $row['total'];

                    $total_male += $male;
                    $total_female += $female;
                    $grand_total += $total;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($department_name, ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= $male ?></td>
                        <td><?= $female ?></td>
                        <td><?= $total ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr style="font-weight: bold;">
                    <td style="font-weight: bold;">ጠቅላላ</td>
                    <td><?= $total_male ?></td>
                    <td><?= $total_female ?></td>
                    <td><?= $grand_total ?></td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>
    
    <!-- choir category based -->
    <?php if (is_array($selected_data) && in_array('choir_category_report', $selected_data)): ?>
        <h2 class="report-section-header">የተማሪዎች ቁጥር በመዝሙር ክፍል</h2>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>መዝሙር ክፍል</th>
                    <th>ወንድ</th>
                    <th>ሴት</th>
                    <th>ጠቅላላ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_male = 0;
                $total_female = 0;
                $grand_total = 0;

                foreach ($choir_category_report as $row):
                    $choir_name = $row['choir_name'] ?? 'Unknown';
                    $male = $row['male'];
                    $female = $row['female'];
                    $total = $row['total'];

                    $total_male += $male;
                    $total_female += $female;
                    $grand_total += $total;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($choir_name, ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= $male ?></td>
                        <td><?= $female ?></td>
                        <td><?= $total ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr style="font-weight: bold;">
                    <td style="font-weight: bold;">ጠቅላላ</td>
                    <td><?= $total_male ?></td>
                    <td><?= $total_female ?></td>
                    <td><?= $grand_total ?></td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>
    
   


</body>

</html>