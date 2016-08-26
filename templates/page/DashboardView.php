<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

    $view = $_REQUEST['page_info']['view'];
    $current_user = wp_get_current_user();
    $current_user_party = PartyAPI::get_user_party($current_user->ID);
    //$dashboard_stats = DashboardService::get_party_dashboard_stats($current_user_party['id']);
?>
    <div class="mini-charts">
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="mini-charts-item bgm-cyan">
                    <div class="clearfix">
                        <div class="chart stats-bar"></div>
                        <div class="count">
                            <small>Number Of Properties</small>
                            <h2><?php //echo $dashboard_stats['property_count'];?></h2>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-md-3">
                <div class="mini-charts-item bgm-lightgreen">
                    <div class="clearfix">
                        <div class="chart stats-bar-2"></div>
                        <div class="count">
                            <small>Number Of Tenants</small>
                            <h2><?php //echo $dashboard_stats['tenants_count'];?></h2>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-md-3">
                <div class="mini-charts-item bgm-orange">
                    <div class="clearfix">
                        <div class="chart stats-line"></div>
                        <div class="count">
                            <small>Lease Agreements Value</small>
                            <h2><?php //echo get_option('cp_currency_symbol');?>  <?php //echo $dashboard_stats['lease_agreements_value'];?></h2>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-md-3">
                <div class="mini-charts-item bgm-bluegray">
                    <div class="clearfix">
                        <div class="chart stats-line-2"></div>
                        <div class="count">
                            <small>Monthly Rent Income</small>
                            <h2><?php //echo $dashboard_stats['current_monthly_rent_income'];?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="card">
        <div class="card-header">
            <h2>Property Performance Statistics </h2>
            
            <ul class="actions">
                <li>
                    <a href="index.html">
                        <i class="md md-cached"></i>
                    </a>
                </li>
                <li>
                    <a href="index.html">
                        <i class="md md-file-download"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="index.html" data-toggle="dropdown">
                        <i class="md md-more-vert"></i>
                    </a>
                    
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                            <a href="index.html">Change Date Range</a>
                        </li>
                        <li>
                            <a href="index.html">Change Graph Type</a>
                        </li>
                        <li>
                            <a href="index.html">Other Settings</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        
        <div class="card-body">
            <div class="chart-edge">
                <div id="curved-line-chart" class="flot-chart "></div>
            </div>
        </div>
    </div>
