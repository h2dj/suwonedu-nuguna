<?php
/**
 * vi:set sw=4 ts=4 noexpandtab fileencoding=utf8:
 * @class  newpostsAdminView
 * @author NURIGO (Contact@nurigo.net)
 * @brief  newpostsAdminView
 */ 
class newpostsAdminView extends newposts 
{
	var $group_list;

	/**
	 * @brief Constructor 
	 *
	 **/
	function init() 
	{
		$oMemberModel = &getModel('member');

		// group 목록 가져오기
		$this->group_list = $oMemberModel->getGroups();
		Context::set('group_list', $this->group_list);

		// 템플릿 설정
		$this->setTemplatePath($this->module_path.'tpl');
	}

	/**
	 * @brief newposts configuration list.
	 *
	 **/
	function dispNewpostsAdminList() 
	{
		$config_list = array();
		$args->page = Context::get('page');
		$output = executeQueryArray('newposts.getConfigList', $args);
		if (!$output->toBool()) return $output; 
		
		foreach ($output->data as $no => $val) 
		{
			$val->no = $no;
			$val->module_info = array();
			$config_list[$val->config_srl] = $val;
		}

		Context::set('total_count', count($output->data));
		Context::set('total_page', $output->total_page);
		Context::set('page', $output->page);
		Context::set('page_navigation', $output->page_navigation);

		// module infos
		if (count($config_list) > 0) 
		{
			$config_srls = array_keys($config_list);
			$config_srls = join(',', $config_srls);

			$query_id = "newposts.getModuleInfoByConfigSrl";
			$args->config_srls = $config_srls;
			$output = executeQueryArray($query_id, $args);
			if ($output->data) 
			{
				foreach ($output->data as $no => $val) 
				{
					$config_list[$val->config_srl]->module_info[] = $val;
				}
			}
		}
		debugprint($config_list);
		Context::set('list', $config_list);
		$this->setTemplateFile('list');
	}

	/**
	 * @brief insert newposts configuration info.
	 *
	 **/
	function dispNewpostsAdminInsert() 
	{
		$oNewpostsModel = &getModel('newposts');
		$oEditorModel = &getModel('editor');

		$config = $oEditorModel->getEditorConfig(0);
		// set editor options.
		$option->skin = $config->editor_skin;
		$option->content_style = $config->content_style;
		$option->content_font = $config->content_font;
		$option->content_font_size = $config->content_font_size;
		$option->colorset = $config->sel_editor_colorset;
		$option->allow_fileupload = true;
		$option->enable_default_component = true;
		$option->enable_component = true;
		$option->disable_html = false;
		$option->height = 200;
		$option->enable_autosave = false;
		$option->primary_key_name = 'noti_srl';
		$option->content_key_name = 'mail_content';

		$editor = $oEditorModel->getEditor(0, $option);
		Context::set('editor', $editor);

		// senderIds
		$sender_ids = $oNewpostsModel->getRegisteredSenderIds();
		Context::set('sender_ids', $sender_ids);
		
		$config->content = Context::getLang('default_content');
		$config->mail_content = Context::getLang('default_mail_content');
		Context::set('config', $config);

		$this->setTemplateFile('insert');
	}

	/**
	 * @brief modify newposts configuration.
	 *
	 **/
	function dispNewpostsAdminModify() 
	{
		$oNewpostsModel = &getModel('newposts');
		$config_srl = Context::get('config_srl');
		// load newposts info
		$args->config_srl = $config_srl;
		$output = executeQuery("newposts.getConfig", $args);
		if(!$output->toBool()) return $output;

		$config = $output->data;
		if(!$config) return new Object(-1, 'Can not read config information');

		$extra_vars = unserialize($config->extra_vars);
		if ($extra_vars) 
		{
			foreach ($extra_vars as $key => $val) 
			{
				$config->{$key} = $val;
			}
		}
		// load module srls
		$args->config_srl = $config_srl;
		$output = executeQueryArray("newposts.getModuleSrls", $args);
		if (!$output->toBool()) return $output;
		$module_srls = array();
		if ($output->data) 
		{
			foreach ($output->data as $no => $val) 
			{
				$module_srls[] = $val->module_srl;
			}
		}

		$config->module_srls = $module_srls[0];
		if(sizeOf($module_srls)!=0) $config->module_srls = join(',', $module_srls);
		Context::set('config', $config);

		// senderIds
		$sender_ids = $oNewpostsModel->getRegisteredSenderIds();
		Context::set('sender_ids', $sender_ids);

		// editor
		$oEditorModel = &getModel('editor');
		$config = $oEditorModel->getEditorConfig(0);
		// set options.
		$option->skin = $config->editor_skin;
		$option->content_style = $config->content_style;
		$option->content_font = $config->content_font;
		$option->content_font_size = $config->content_font_size;
		$option->colorset = $config->sel_editor_colorset;
		$option->allow_fileupload = true;
		$option->enable_default_component = true;
		$option->enable_component = true;
		$option->disable_html = false;
		$option->height = 200;
		$option->enable_autosave = false;
		$option->primary_key_name = 'config_srl';
		$option->content_key_name = 'mail_content';
		$editor = $oEditorModel->getEditor($config_srl, $option);
		Context::set('editor', $editor);

		$this->setTemplateFile('modify');
	}

	/**
	 * @brief display 분류별 담당자 지정 페이지
	 *
	 **/
	function dispNewpostsAdminSetCategoryAdmins()
	{
		//config 정보 가져오기
		$config_srl = Context::get('config_srl');
		$args->config_srl = $config_srl;
		$output = executeQuery("newposts.getConfig", $args);
		if(!$output->toBool()) return $output;
		$config = $output->data;
		$output = executeQueryArray("newposts.getModuleSrls", $args);
		if (!$output->toBool()) return $output;
		$module_srls = array();

		if ($output->data) 
		{
			foreach ($output->data as $val) 
			{
				$module_srls[] = $val->module_srl;
			}
		}
		$tmpOutput = array();
		$nextOutput = array();

		for($i=0; $i<sizeOf($module_srls); $i++)
		{
			$args->module_srl = $module_srls[$i];
			//get Browser title
			$module_info = executeQuery("newposts.getModuleInfoByModuleSrl", $args);
			if(!$module_info->toBool()) return $module_info;
			//get Category_srl & title
			$output = executeQuery("newposts.getDocumentCategories", $args);
			if(!$output->toBool()) return $output;

			// $nextOutput 에 넣을 Object 생성 
			$obj = new stdClass();
			$obj->title = $module_info->data->browser_title;
			$obj->data = array();

			if(is_array($output->data))
			{
				foreach($output->data as $no => $val)
				{
					$args->category_srl = $val->category_srl;
					$args->parent_srl = $val->parent_srl;
					$args->title = $val->title;
					$out = executeQuery("newposts.insertAdminInfo", $args);
					if(!$out->toBool()) {
						executeQuery("newposts.updateAdminInfo", $args);
					}
					$tmpOutput = executeQuery("newposts.getAdminInfo", $args);
					if(!$tmpOutput->toBool()) return $tmpOutput;

					$obj->data[] = $tmpOutput->data;
					
				}
			}
			else
			{
				$args->category_srl = $output->data->category_srl;
				$args->parent_srl = $output->data->parent_srl;
				$args->title = $output->data->title;
				$out = executeQuery("newposts.insertAdminInfo", $args);
				if(!$out->toBool()) {
					executeQuery("newposts.updateAdminInfo", $args);
				}
				$tmpOutput = executeQuery("newposts.getAdminInfo", $args);
				if(!$tmpOutput->toBool()) return $tmpOutput;
				$obj->data[] = $tmpOutput->data;
			}
			//분류가 없는 게시판은 $nextOutput 에서 뺀다
			if(count($output->data)!=0)
			{
				$nextOutput[$module_info->data->module_srl] = $obj;
			}
			else
			{
				array_splice($nextOutput, $i, 1);
			}
		}
		// re-arrange the outputs according to Parent -> child
		$this->arrangeElement($nextOutput);
		Context::set('config_srl', $config_srl);
		Context::set('outputs', $nextOutput);
		$this->setTemplateFile('set_category_admins');
	}

	/**
	 * @brief tool for arranging elements
	 *
	 **/
	function arrangeElement(&$array)
	{
		$i = 0;
		$copyArray = array();
		$keys = array();
		$sortedData = array();
		foreach($array as $data)
		{
			foreach($data as $key)
			{
				if($key->parent_srl != 0)
				{
					foreach($data as $val)
					{
						if($val->category_srl == $key->parent_srl)
						{
							$insert_index = array_keys($data, $val);
							$remove_index = array_keys($data, $key);

							$out = array_splice($data, $remove_index[0], 1);
							array_splice($data, $insert_index[0]+1, 0, $out);	
							//$array	= $data;
						}
					}
				}

			}
			$keys = array_keys($array);
			$sortedData[$keys[$i]] = $data;
			$i++;
		}
		$array = $sortedData;	
	}
}
/* End of file newposts.admin.view.php */
/* Location: ./modules/newposts/newposts.admin.view.php */
