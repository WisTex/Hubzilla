<?php

/**
 *   * Name: Affinity Tool
 *   * Description: Filter the network stream by affinity, requires the Affinity Tool App
 *   * Requires: network
 */

namespace Zotlabs\Widget;

use Zotlabs\Lib\Apps;

class Affinity {

	function widget($arr) {

		if(! local_channel())
			return;

		if(! Apps::system_app_installed(local_channel(),'Affinity Tool'))
			return;

		$default_cmin = ((Apps::system_app_installed(local_channel(),'Affinity Tool')) ? get_pconfig(local_channel(),'affinity','cmin',0) : 0);
		$default_cmax = ((Apps::system_app_installed(local_channel(),'Affinity Tool')) ? get_pconfig(local_channel(),'affinity','cmax',99) : 99);

		$cmin = ((x($_REQUEST,'cmin')) ? intval($_REQUEST['cmin']) : $default_cmin);
		$cmax = ((x($_REQUEST,'cmax')) ? intval($_REQUEST['cmax']) : $default_cmax);

		$affinity_locked = intval(get_pconfig(local_channel(),'affinity','lock',1));
		if ($affinity_locked) {
			set_pconfig(local_channel(),'affinity','cmin',$cmin);
			set_pconfig(local_channel(),'affinity','cmax',$cmax);
		}

		$labels = array(
			t('Me'),
			t('Family'),
			t('Friends'),
			t('Acquaintances'),
			t('All')
		);
		call_hooks('affinity_labels',$labels);

		$label_str = '';

		if($labels) {
			foreach($labels as $l) {
				if($label_str) {
					$label_str .= ", '|'";
					$label_str .= ", '" . $l . "'";
				}
				else
					$label_str .= "'" . $l . "'";
			}
		}

		$tpl = get_markup_template('main_slider.tpl');
		$x = replace_macros($tpl,array(
			'$val' => $cmin . ',' . $cmax,
			'$refresh' => t('Refresh'),
			'$labels' => $label_str,
		));

		$arr = array('html' => $x);
		call_hooks('main_slider',$arr);

		return $arr['html'];


	}
}

