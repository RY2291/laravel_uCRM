<?php
namespace App\Services;

interface AnalysisServiceInterface
{
  public function perDay($subQuery);

  public function perMonth($subQuery);

  public function perYear($subQuery);
}