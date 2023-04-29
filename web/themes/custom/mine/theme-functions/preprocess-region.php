<?php

function mine_preprocess_region(&$variables) {
  $variables['attributes']['class'][] = 'region';
  $variables['attributes']['class'][] = 'region-' . str_replace('_', '-', $variables['region']);
}