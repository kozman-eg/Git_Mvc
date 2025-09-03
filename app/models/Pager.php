<?php

namespace Models;

trait Pager
{
    
    public $links         = [];    // الصفحة الحالية
    public $offset        = 0;     // حساب البداية (start) للـ LIMIT في SQL
    public $page_number   = 1;     // الصفحة الحالية
    public $start         = 1;     // الصفحة البدايه
    public $end           = 1;     // الصفحة الاخيره
    public $limit         = 10;    // عدد العناصر المسموح بها في كل صفحة
    public $nav_class     = "";
    public $nav_styles     = "";
    public $li_class      = "page-item";
    public $li_styles        = "page-item";
    public $ul_class      = "pagination justify-content-center";
    public $ul_styles       = "pagination justify-content-center";
    public $a_class       = "page-link";
    public $a_styles         = "page-link";
    public $count_links   = 5;     // عدد الروابط التي ستظهر في شريط الصفحات
    public $total_records = 0;     // عدد العناصر في كل صفحة

    public $first_a_class      = "page-item";
    public $first_a_styles       = "page-item";
    public $first_i_class      = "page-item";
    public $first_i_styles     = "page-item";

    public $next_a_class      = "page-item";
    public $next_a_styles       = "page-item";
    public $next_i_class      = "page-item";
    public $next_i_styles     = "page-item";

    public $active_class      = "page-item";
    public $activestyles       = "page-item";

    public function __construct($limit = 10, $extras = 2)
    {
        $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page_number = max(1, $page_number);

        $this->page_number = $page_number;
        $this->limit = $limit;
        $this->offset = ($page_number - 1) * $limit;

        $total_pages = $this->total_pages();
        $this->end   = min($total_pages, $page_number + $extras);
        $this->start = max(1, $page_number - $extras);

        $url = $_GET['url'] ?? '';
        $current_link = ROOT . "/" . $url . "?" . trim(str_replace('url=', "", str_replace($url, "", $_SERVER['QUERY_STRING'])), '&');
        $current_link = !strstr($current_link, "page=") ? $current_link . "&page=1" : $current_link;

        if (!strstr($current_link, "?")) {
            $current_link = str_replace("&page", "?page", $current_link);
        }

        $first_link = preg_replace('/page=[0-9]+/', "page=1", $current_link);
        $next_link  = preg_replace('/page=[0-9]+/', "page=" . min($total_pages, $page_number + 1), $current_link);
    
        //$next_link  = preg_replace('/page=[0-9]+/', "page=" . min($total_pages, $extras + 1), $current_link);


        $this->links['current'] = $current_link;
        $this->links['first']   = $first_link;
        $this->links['next']    = $next_link;
    }

    public function total_pages()
    {
        return max(1, ceil($this->total_records / $this->limit));
    }

    public function display($record_count = null)
    {
        if ($record_count === null) {
            $record_count = $this->limit;
        }
        if ($record_count == $this->limit || $this->page_number > 1) {
?>
            <br class="clearfix">
            <div>
                <nav class="<?= $this->nav_class ?>" styles="<?= $this->nav_styles ?>">
                    <ul class="<?= $this->ul_class ?>" styles="<?= $this->ul_styles ?>">
                        <li class="<?= $this->first_i_class?>" styles="<?= $this->first_i_styles ?>"><a class="<?= $this->first_a_classa_class ?>" styles="<?= $this->first_a_styles ?>" href="<?= $this->links['first'] ?>">First</a></li>

                        <?php for ($x = $this->start; $x <= $this->end; $x++): ?>
                            <li styles="<?= $this->li_styles?> <?= ($x == $this->page_number) ?  $this->active : '';?>" class="<?=$this->li_class?>">
                                <a class="<?= $this->a_class ?>" class="<?= $this->a_class ?>" href="<?= preg_replace('/page=[0-9]+/', "page=" . $x, $this->links['current']) ?>"><?= $x ?></a>
                            </li>
                        <?php endfor; ?>

                        <li class="<?= $this->next_i_class ?>"styles="<?= $this->next_i_styles ?>"><a class="<?= $this->next_a_class ?>"styles="<?= $this->styles_a_class ?>" href="<?= $this->links['next'] ?>">Next</a></li>
                    </ul>
                </nav>
            </div>
<?php
        }
    }


    // توليد روابط الصفحات
    public function links($base_url)
    {
        $total_pages = $this->total_pages();
        if ($total_pages <= 1) return '';

        $output = '<div class="pagination">';
        $start = $this->start;
        $end = $this->end;

        if ($this->page_number > 1) {
            $output .= '<a href="' . $base_url . '?page=' . ($this->page_number - 1) . '">&laquo; السابق</a>';
        }

        for ($i = $start; $i <= $end; $i++) {
            $class = ($i == $this->page_number) ? 'class="active"' : '';
            $output .= '<a ' . $class . ' href="' . $base_url . '?page=' . $i . '">' . $i . '</a>';
        }

        if ($this->page_number < $total_pages) {
            $output .= '<a href="' . $base_url . '?page=' . ($this->page_number + 1) . '">التالي &raquo;</a>';
        }

        $output .= '</div>';
        return $output;
    }
}
