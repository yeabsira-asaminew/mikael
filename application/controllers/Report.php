<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . '../vendor/autoload.php';

use Mpdf\Mpdf;

use Andegna\DateTime as EthiopianDateTime;
use Andegna\DateTimeFactory;
use Andegna\Constants;

class Report extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('uid')) {
            redirect('login');
        } elseif ($this->session->userdata('role') !== 'superadmin') {
            redirect('login');
        }


        $this->load->model('Report_model');
        $this->load->helper('file');
        $this->load->helper('download');
        $this->load->dbutil();
    }

    public function index()
    {
        $this->load->view('admin/report_form');
    }

    public function generate_report()
    {
        // Get selected data from the form (ensure it's always an array)
        $selected_data = $this->input->post('data') ?? [];

        // Fetch data from the database based on selection
        $data = [
            'selected_data' => $selected_data, // Pass selected data to the view
            'age_group_report' => $this->Report_model->get_students_by_age_group(),
            'student_status_report' => $this->Report_model->get_students_by_status(),
            'apostolic_category_report' => $this->Report_model->get_students_by_apostolic(),
            'curriculum_category_report' => $this->Report_model->get_students_by_curriculum(),
            'department_category_report' => $this->Report_model->get_students_by_department(),
            'choir_category_report' => $this->Report_model->get_students_by_choir(),
        ];

        $selected_years = $this->input->post('years');
        $reportData = [];

        foreach ($selected_years as $ethiopianYear) {
            // Convert Ethiopian year to Gregorian range
            $startDate = DateTimeFactory::of((int)$ethiopianYear, 1, 1)->toGregorian();
            $endDate = DateTimeFactory::of((int)$ethiopianYear, 12, 30)->toGregorian();
            $startGregorianYear = $startDate->format('Y');
            $endGregorianYear = $endDate->format('Y');

            // Get counts
            $reportData[$ethiopianYear] = $this->Report_model->get_student_count_by_year_range($startGregorianYear, $endGregorianYear);
        }

        // Prepare date & time
        $now = new DateTime('now', new DateTimeZone('Africa/Addis_Ababa'));
        $ethiopian_date = new EthiopianDateTime($now);
        $ethiopian_date_str = $ethiopian_date->format('F j, Y');

        $adjustedTime = clone $now;
        $adjustedTime->modify('-6 hours');
        $timeStr = $adjustedTime->format('g:i A');
        $timeStr = str_replace(['AM', 'PM'], ['ቀን', 'ማታ'], $timeStr);
        $ethiopian_date_str .= ", $timeStr";

        // Load PDF view
        $data['reportData'] = $reportData;
        $html = $this->load->view('admin/report_template', $data, true);

        try {
            $mpdf = new Mpdf();
            $mpdf->SetHeader('ርእሰ አድባራት ወገዳማት ደብረ ምሕረት ቅዱስ ሚካኤል ካቴድራል ዐምደ ሃይማኖት ሰ/ት/ቤት');
            $mpdf->SetFooter('ሪፖርቱ የተፈጠረው በሰንበት አቴንዳንስ ሲስተም(SAS) በ' . $ethiopian_date_str);
            $mpdf->WriteHTML($html);
            $filename = "student_report_{$ethiopian_date_str}.pdf";
            $mpdf->Output($filename, 'D');
        } catch (Exception $e) {
            echo "PDF generation failed: " . $e->getMessage();
        }
    }

    public function backup_db()
    {
        $prefs = array(
            'format' => 'zip',
            'filename' => 'senbet_db.sql'
        );
        $backup = $this->dbutil->backup($prefs);

        $filename = 'senbet_db_backup-' . date('Y-m-d_H-i-s') . '.zip';

        force_download($filename, $backup);
    }
}
