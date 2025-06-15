<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicon -->
    <link rel="icon" href="<?php echo base_url('assets/images/favicon.ico'); ?>" type="image/x-icon">
    <title>ዳሽቦርድ | ሰንበት አቴንዳንስ ሲስተም</title>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .charts-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            justify-content: center;
            align-items: center;
        }

        canvas {
            max-width: 300px !important;
            max-height: 300px !important;
            margin: auto;
        }
    </style>

</head>

<body>

    <?php include APPPATH . 'views/admin/includes/sidebar.php'; ?>

    <!-- CONTENT -->
    <section id="content">
        <?php include APPPATH . 'views/admin/includes/topbar.php'; ?>

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h2>እንኳን ወደ የርእሰ አድባራት ወገዳማት ደብረ ምሕረት ቅዱስ ሚካኤል ካቴድራል ዐምደ ሃይማኖት ሰ/ት/ቤት አቴንዳንስ ሲስተም በደህና መጡ</h2>
                    <ul class="breadcrumb">
                        <li>
                            <a class="active" href="<?php echo base_url('dashboard'); ?>">ዳሽቦርድ</a>
                        </li>

                    </ul>
                </div>
            </div>

            <div>
                <div class="charts-container"
                    style="background: white; border-radius: 10px; padding-top: 25px; margin: 10px auto; ">
                    <canvas id="sexChart"></canvas>
                    <canvas id="ageChart"></canvas>
                    <canvas id="apostolicChart"></canvas>
                    <canvas id="choirChart"></canvas>
                    <canvas id="curriculumChart"></canvas>
                    <canvas id="departmentChart"></canvas>
                    <canvas id="occupationChart"></canvas>
                    <canvas id="educationChart"></canvas>
                    <canvas id="attendanceChart"></canvas>
                    <canvas id="yearlyChart"></canvas>

                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Data variables
            const sexData = <?= json_encode($sex_data) ?>;
            const ageData = <?= json_encode($age_data) ?>;
            const apostolicData = <?= json_encode($apostolic_data) ?>;
            const choirData = <?= json_encode($choir_data) ?>;
            const curriculumData = <?= json_encode($curriculum_data) ?>;
            const departmentData = <?= json_encode($department_data) ?>;
            const attendanceData = <?= json_encode($attendance_data) ?>;
            const yearlyData = <?= json_encode($students_by_year) ?>;
            const occupationData = <?= json_encode($occupation_data) ?>;
            const educationData = <?= json_encode($education_data) ?>;

            // Unified color palette with consistent assignments
            const colorPalette = {
                // Base colors for common categories
                male: '#36A2EB',
                female: '#FF6384',
                present: '#4BC0C0',
                absent: '#FF9F40',

                // Sequential colors for other categories
                sequential: [
                    '#9966FF', '#FFCE56', '#8AC926', '#1982C4',
                    '#FF5733', '#33FF57', '#3357FF', '#F033FF',
                    '#33FFF0', '#FF33A1', '#A133FF', '#FFC133',
                    '#33FFA1', '#FF3333', '#8C6AFF', '#66FF8C'
                ]
            };

            // Helper function to get consistent colors
            function getColor(category, index = 0) {
                if (category === 'male') return colorPalette.male;
                if (category === 'female') return colorPalette.female;
                if (category === 'present') return colorPalette.present;
                if (category === 'absent') return colorPalette.absent;
                return colorPalette.sequential[index % colorPalette.sequential.length];
            }

            // Chart 1: Students by Sex (pie)
            new Chart(document.getElementById('sexChart').getContext('2d'), {
                type: 'pie',
                data: {
                    labels: sexData.map(item => item.sex_amharic),
                    datasets: [{
                        data: sexData.map(item => Math.round(item.count)),
                        backgroundColor: sexData.map(item =>
                            item.sex_amharic === 'ወንድ' ? getColor('male') : getColor('female')
                        )
                    }]
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'የተማሪዎች ስርጭት - በጾታ',
                            font: {
                                size: 16
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10
                            }
                        }
                    }
                }
            });

            // Chart 2: Students by Age Group (Pie)
            new Chart(document.getElementById('ageChart').getContext('2d'), {
                type: 'pie',
                data: {
                    labels: ['ደቂቅ', 'ቂርቆስ', 'ማዕከላዊ', 'አዳጊ ወጣት', 'ወጣት', 'ነባር'],
                    datasets: [{
                        data: [
                            Math.round(ageData.under_7),
                            Math.round(ageData.between_7_11),
                            Math.round(ageData.between_11_16),
                            Math.round(ageData.between_16_19),
                            Math.round(ageData.young),
                            Math.round(ageData.adult)
                        ],
                        backgroundColor: [
                            getColor(null, 0), getColor(null, 1), getColor(null, 2),
                            getColor(null, 3), getColor(null, 4), getColor(null, 5)
                        ]
                    }]
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'የተማሪዎች ስርጭት - በእድሜ',
                            font: {
                                size: 16
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10
                            }
                        }
                    }
                }
            });

            // Apostolic Chart (Pie)
            new Chart(document.getElementById('apostolicChart').getContext('2d'), {
                type: 'pie',
                data: {
                    labels: apostolicData.map(item => item.apostolic_name || 'አልተመደበም'),
                    datasets: [{
                        data: apostolicData.map(item => item.count),
                        backgroundColor: apostolicData.map((_, i) => getColor(null, i))
                    }]
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'የተማሪዎች ስርጭት - በሐዋርያት ክፍል',
                            font: {
                                size: 16
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                font: {
                                    size: 11
                                },
                                padding: 5
                            }
                        }
                    }
                }
            });


            // Choir Chart (pie)
            new Chart(document.getElementById('choirChart').getContext('2d'), {
                type: 'pie',
                data: {
                    labels: choirData.map(item => item.choir_name || 'አልተመደበም'),
                    datasets: [{
                        data: choirData.map(item => item.count),
                        backgroundColor: choirData.map((_, i) => getColor(null, i + 2)) // Offset by 2
                    }]
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'የተማሪዎች ስርጭት - በመዝሙር ክፍል',
                            font: {
                                size: 16
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10
                            }
                        }
                    }
                }
            });

            // Curriculum Chart (Pie)
            new Chart(document.getElementById('curriculumChart').getContext('2d'), {
                type: 'pie',
                data: {
                    labels: curriculumData.map(item => item.curriculum_name || 'አልተመደበም'),
                    datasets: [{
                        data: curriculumData.map(item => item.count),
                        backgroundColor: curriculumData.map((_, i) => getColor(null, i + 4)) // Offset by 4
                    }]
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'የተማሪዎች ስርጭት - ስርአተ ትምህርት',
                            font: {
                                size: 16
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10
                            }
                        }
                    }
                }
            });

            // Department Chart (pie)
            new Chart(document.getElementById('departmentChart').getContext('2d'), {
                type: 'pie',
                data: {
                    labels: departmentData.map(item => item.department_name || 'አልተመደበም'),
                    datasets: [{
                        data: departmentData.map(item => item.count),
                        backgroundColor: departmentData.map((_, i) => getColor(null, i + 6)) // Offset by 6
                    }]
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'የተማሪዎች ስርጭት - በአገልግሎት ክፍል',
                            font: {
                                size: 16
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10
                            }
                        }
                    }
                }
            });

            // Occupation Chart
            if (occupationData && occupationData.length > 0) {
                new Chart(document.getElementById('occupationChart').getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: occupationData.map(item => item.occupation),
                        datasets: [{
                            data: occupationData.map(item => item.count),
                            backgroundColor: occupationData.map((_, i) => getColor(null, i + 8)) // Offset by 8
                        }]
                    },
                    options: {
                        plugins: {
                            title: {
                                display: true,
                                text: 'የተማሪዎች ስርጭት በሥራ',
                                font: {
                                    size: 16
                                }
                            },
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 10
                                }
                            }
                        }
                    }
                });
            }

            // Education Level Chart
            if (educationData && educationData.length > 0) {
                new Chart(document.getElementById('educationChart').getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: educationData.map(item => item.education_level),
                        datasets: [{
                            data: educationData.map(item => item.count),
                            backgroundColor: educationData.map((_, i) => getColor(null, i + 10)) // Offset by 10
                        }]
                    },
                    options: {
                        plugins: {
                            title: {
                                display: true,
                                text: 'የተማሪዎች ስርጭት በትምህርት ደረጃ',
                                font: {
                                    size: 16
                                }
                            },
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 10
                                }
                            }
                        }
                    }
                });
            }

            // Attendance Chart (Bar)
            new Chart(document.getElementById('attendanceChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['አጠቃላይ', 'ያለፈው ወር', 'ያለፈው ሳምንት'],
                    datasets: [{
                            label: 'ተገኝቷል',
                            data: [
                                Math.round(attendanceData.present),
                                Math.round(attendanceData.present_last_month),
                                Math.round(attendanceData.present_last_week)
                            ],
                            backgroundColor: getColor('present')
                        },
                        {
                            label: 'አልተገኘም',
                            data: [
                                Math.round(attendanceData.absent),
                                Math.round(attendanceData.absent_last_month),
                                Math.round(attendanceData.absent_last_week)
                            ],
                            backgroundColor: getColor('absent')
                        }
                    ]
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'አቴንዳንስ ትንተና',
                            font: {
                                size: 16
                            }
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: function(value) {
                                    return Number.isInteger(value) ? value : '';
                                }
                            }
                        }
                    }
                }
            });

            // Yearly Students Chart (Line)
            new Chart(document.getElementById('yearlyChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: yearlyData.map(item => item.year),
                    datasets: [{
                        label: 'ተማሪዎች',
                        data: yearlyData.map(item => Math.round(item.count)),
                        borderColor: getColor(null, 12), // Consistent with palette
                        backgroundColor: hexToRgba(getColor(null, 12), 0.2),
                        borderWidth: 2,
                        tension: 0.1
                    }]
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'የተመዘገቡ ተማሪዎች ቁጥር በየአመቱ',
                            font: {
                                size: 16
                            }
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: function(value) {
                                    return Number.isInteger(value) ? value : '';
                                }
                            }
                        }
                    }
                }
            });

            // Helper function to convert hex to rgba
            function hexToRgba(hex, alpha) {
                const r = parseInt(hex.slice(1, 3), 16);
                const g = parseInt(hex.slice(3, 5), 16);
                const b = parseInt(hex.slice(5, 7), 16);
                return `rgba(${r}, ${g}, ${b}, ${alpha})`;
            }
        });
    </script>

</body>

<script src="<?php echo base_url('assets/js/script.js'); ?>"></script>

</html>