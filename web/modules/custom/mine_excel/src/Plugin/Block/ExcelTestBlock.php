<?php

namespace Drupal\mine_excel\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Markup;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Provides a block to render an Excel sheet as a table.
 *
 * @Block(
 *   id = "excel_table_block",
 *   admin_label = @Translation("Excel Table Block"),
 *   category = @Translation("Custom")
 * )
 */
class ExcelTestBlock extends BlockBase {
  use StringTranslationTrait;

  public function defaultConfiguration() {
    return [
      'file_path'   => 'private://data/my-sheet.xlsx', // adjust to your server
      'worksheet'   => '',
      'header_row'  => 1,     // 1-based, 0 = no header
      'start_cell'  => '',    // e.g. A1
      'end_cell'    => '',    // e.g. G100
      'trim_empty'  => TRUE,
    ];
  }

  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();

    $form['file_path'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Excel file path'),
      '#description' => $this->t('Use a stream wrapper path like private:// or public://, or an absolute path on the server.'),
      '#default_value' => $config['file_path'],
      '#required' => TRUE,
    ];
    $form['worksheet'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Worksheet name'),
      '#description' => $this->t('Leave blank to use the first worksheet.'),
      '#default_value' => $config['worksheet'],
    ];
    $form['header_row'] = [
      '#type' => 'number',
      '#title' => $this->t('Header row number'),
      '#description' => $this->t('1-based row index containing column headers. Set 0 if your sheet has no header row.'),
      '#default_value' => (int) $config['header_row'],
      '#min' => 0,
    ];
    $form['start_cell'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Start cell'),
      '#description' => $this->t('Optional. E.g. A1'),
      '#default_value' => $config['start_cell'],
    ];
    $form['end_cell'] = [
      '#type' => 'textfield',
      '#title' => $this->t('End cell'),
      '#description' => $this->t('Optional. E.g. G100'),
      '#default_value' => $config['end_cell'],
    ];
    $form['trim_empty'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Trim empty trailing rows/columns'),
      '#default_value' => (bool) $config['trim_empty'],
    ];

    return $form;
  }

  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfigurationValue('file_path', $form_state->getValue('file_path'));
    $this->setConfigurationValue('worksheet', $form_state->getValue('worksheet'));
    $this->setConfigurationValue('header_row', (int) $form_state->getValue('header_row'));
    $this->setConfigurationValue('start_cell', $form_state->getValue('start_cell'));
    $this->setConfigurationValue('end_cell', $form_state->getValue('end_cell'));
    $this->setConfigurationValue('trim_empty', (bool) $form_state->getValue('trim_empty'));
  }

  public function build() {
    $c = $this->getConfiguration();
    $path = $c['file_path'];

    // Resolve stream wrapper URIs to real paths where possible.
    try {
      $real = \Drupal::service('file_system')->realpath($path);
    } catch (\Exception $e) {
      $real = FALSE;
    }

    if (!$real || !file_exists($real)) {
      return [
        '#markup' => Markup::create('<em>' . $this->t('Excel file not found: @p', ['@p' => $path]) . '</em>'),
        '#cache' => ['max-age' => 0],
      ];
    }

    // Load the spreadsheet.
    try {
      $reader = IOFactory::createReaderForFile($real);
      // Security: only read data, not macros etc.
      if (method_exists($reader, 'setReadDataOnly')) {
        $reader->setReadDataOnly(true);
      }
      $spreadsheet = $reader->load($real);

      $sheet = NULL;
      if (!empty($c['worksheet'])) {
        $sheet = $spreadsheet->getSheetByName($c['worksheet']);
        if (!$sheet) {
          return [
            '#markup' => Markup::create('<em>' . $this->t('Worksheet "@name" not found.', ['@name' => $c['worksheet']]) . '</em>'),
            '#cache' => ['max-age' => 0],
          ];
        }
      } else {
        $sheet = $spreadsheet->getSheet(0);
      }

      // Range selection.
      $range = '';
      if (!empty($c['start_cell']) && !empty($c['end_cell'])) {
        $range = $c['start_cell'] . ':' . $c['end_cell'];
      }

      $data = $sheet->rangeToArray(
        $range ?: $sheet->calculateWorksheetDimension(),
        null,   // nullValue
        true,   // calculateFormulas
        true,   // formatData
        true    // returnCellRef (letters as keys)
      );

      if ($c['trim_empty']) {
        $data = $this->trimEmpty($data);
      }

      if (empty($data)) {
        return [
          '#markup' => Markup::create('<em>' . $this->t('No data found in the selected range.') . '</em>'),
          '#cache' => ['max-age' => 0],
        ];
      }

      // Build header and rows.
      $header = [];
      $rows = [];

      $row_index = 0;
      foreach ($data as $rnum => $row) {
        $row_index++;
        $values = array_values($row); // Convert A,B,C keys to 0..N

        if ((int) $c['header_row'] === $row_index) {
          $header = $values;
          continue;
        }
        $rows[] = $values;
      }

      // If no explicit header row, use first row as data and leave header empty.
      if ((int) $c['header_row'] === 0 && !empty($data)) {
        // Try using first row as header-like placeholders if desired; leaving blank is okay.
        // $header = array_map(fn($i) => $this->t('Column @n', ['@n' => $i+1]), range(0, count(reset($data))-1));
      }

      $table = [
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $rows,
        '#sticky' => true,
        '#attributes' => [
          'class' => ['table', 'excel-table-block'], // bootstrap + custom class
        ],
      ];

      $build = [
        '#type' => 'container',
        '#attributes' => ['class' => ['table-responsive']],
        'table' => $table,
        '#cache' => [
          'max-age' => \Drupal\Core\Cache\Cache::PERMANENT,
          'contexts' => ['url.path'],
          'tags' => ['excel_file:' . md5($real . ':' . filemtime($real))],
        ],
      ];


      return $build;
    } catch (\Throwable $e) {
      // Fail gracefully; do not expose full server paths or stack traces.
      return [
        '#markup' => Markup::create('<em>' . $this->t('Failed to load Excel file.') . '</em>'),
        '#attached' => [
          'library' => [],
        ],
        '#cache' => ['max-age' => 0],
      ];
    }
  }

  /**
   * Trim trailing empty rows/columns.
   *
   * @param array $data
   * @return array
   */
  protected function trimEmpty(array $data): array {
    // Remove completely empty rows.
    $data = array_values(array_filter($data, function ($row) {
      if (!is_array($row)) {
        return false;
      }
      foreach ($row as $cell) {
        if ($cell !== null && $cell !== '') {
          return true;
        }
      }
      return false;
    }));

    if (empty($data)) {
      return $data;
    }

    // Remove trailing empty columns by detecting last non-empty column index.
    $maxIdx = 0;
    foreach ($data as $row) {
      $idx = 0;
      foreach (array_values($row) as $cell) {
        if ($cell !== null && $cell !== '') {
          $maxIdx = max($maxIdx, $idx);
        }
        $idx++;
      }
    }

    $trimmed = [];
    foreach ($data as $row) {
      $vals = array_values($row);
      $trimmed[] = array_slice($vals, 0, $maxIdx + 1);
    }
    return $trimmed;
  }
}
