<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('goods');

/*
**************************
(C)2010-2014 phpMyWind.com
update: 2014-5-30 14:07:16
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__goods';
$gourl  = 'goods.php';
$action = isset($action) ? $action : '';


//添加商品信息
if($action == 'add')
{
	//栏目权限验证
	IsCategoryPriv($classid,'add');


	//初始化参数
	if(!isset($typeid))        $typeid = '-1';
	if(!isset($brandid))       $brandid = '-1';
	if(!isset($attrid))        $attrid = '';
	if(!isset($attrvalue))     $attrvalue = '';
	if(!isset($flag))          $flag   = '';
	if(!isset($picarr))        $picarr = '';
	if(!isset($rempic))        $rempic = '';
	if(!isset($remote))        $remote = '';
	if(!isset($autothumb))     $autothumb = '';
	if(!isset($autodesc))      $autodesc = '';
	if(!isset($autodescsize))  $autodescsize = '';
	if(!isset($autopage))      $autopage = '';
	if(!isset($autopagesize))  $autopagesize = '';
	$attrstr = '';


	//获取parentstr
	$row = $dosql->GetOne("SELECT `parentid` FROM `#@__infoclass` WHERE `id`=$classid");
	$parentid = $row['parentid'];

	if($parentid == 0)
	{
		$parentstr = '0,';
	}
	else
	{
		$r = $dosql->GetOne("SELECT `parentstr` FROM `#@__infoclass` WHERE `id`=$parentid");
		$parentstr = $r['parentstr'].$parentid.',';
	}


	//获取typepstr
	if($typeid != '-1')
	{
		$row = $dosql->GetOne("SELECT `parentid` FROM `#@__goodstype` WHERE `id`=$typeid");
		$typepid = $row['parentid'];
	
		if($typepid == 0)
		{
			$typepstr = '0,';
		}
		else
		{
			$r = $dosql->GetOne("SELECT `parentstr` FROM `#@__goodstype` WHERE `id`=$typepid");
			$typepstr = $r['parentstr'].$typepid.',';
		}
	}
	else
	{
		$typepid  = '-1';
		$typepstr = '';
	}
	
	
	//获取品牌brandpstr
	if($brandid != '-1')
	{
		$row = $dosql->GetOne("SELECT `parentid` FROM `#@__goodsbrand` WHERE `id`=$brandid");
		$brandpid = $row['parentid'];
		if($brandpid == 0)
		{
			$brandpstr = '0,';
		}
		else
		{
			$r = $dosql->GetOne("SELECT `parentstr` FROM `#@__goodsbrand` WHERE `id`=$brandpid");
			$brandpstr = $r['parentstr'].$brandpid.',';
		}
	}
	else
	{
		$brandpid  = '-1';
		$brandpstr = '';
	}


	//商品属性
	if(is_array($attrid) && is_array($attrvalue))
	{
		//组成商品属性与值
		$attrstr .= 'array(';
		$attrids = count($attrid);
		for($i=0; $i<$attrids; $i++)
		{
			$attrstr .= '"'.$attrid[$i].'"=>'.'"'.$attrvalue[$i].'"'; 
			if($i < $attrids-1)
			{
				$attrstr .= ',';
			}
		}
		$attrstr .= ');';
	}
	

	//文章属性
	if(is_array($flag))
	{
		$flag = implode(',',$flag);
	}


	//文章组图
	if(is_array($picarr) &&
	   is_array($picarr_txt))
	{
		$picarrNum = count($picarr);
		$picarrTmp = '';

		for($i=0;$i<$picarrNum;$i++)
		{
			$picarrTmp[] = $picarr[$i].','.$picarr_txt[$i];
		}

		$picarr = serialize($picarrTmp);
	}


	//保存远程缩略图
	if($rempic=='true' and
	   preg_match("#^http:\/\/#i", $picurl))
	{
		$picurl = GetRemPic($picurl);
	}


	//保存远程资源
	if($remote == 'true')
	{
		$content = GetContFile($content);
	}


	//第一个图片作为缩略图
	if($autothumb == 'true')
	{
		$cont_str = stripslashes($content);
		preg_match_all('/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/', $cont_str, $imgurl);

		//如果存在图片
		if(isset($imgurl[1][0]))
		{
			$picurl = $imgurl[1][0];
			$picurl = substr($picurl, strpos($picurl, 'uploads/'));
		}
	}


	//自动提取内容到摘要
	if($autodesc == 'true')
	{
		if(empty($autodescsize) or !intval($autodescsize))
		{
			$autodescsize = 200;
		}

		$descstr     = ClearHtml($content);
		$description = ReStrLen($descstr, $autodescsize);

	}


	//自动分页
    if($autopage == 'true')
    {
        $content = ContAutoPage($content, $autopagesize*1024);
    }


	$posttime = GetMkTime($posttime);


	//自定义字段处理
	$fieldname  = '';
	$fieldvalue = '';
	$fieldstr   = '';

	$ids = GetDiyFieldCatePriv('4',$classid);
	if(!empty($ids))
	{
		$dosql->Execute("SELECT * FROM `#@__diyfield` WHERE infotype=4 AND `id` IN ($ids) AND checkinfo=true ORDER BY orderid ASC");
		while($row = $dosql->GetArray())
		{
			$k = $row['fieldname'];
			if(isset($_POST[$row['fieldname']]))
			{
				if(is_array($_POST[$row['fieldname']]))
				{
					foreach($_POST[$row['fieldname']] as $post_value)
					{
						$v[] = addslashes($post_value);
					}
				}
				else
				{
					$v = addslashes($_POST[$row['fieldname']]);
				}
			}
			else
			{
				$v = '';
			}

			if(!empty($row['fieldcheck']))
			{
				if(!preg_match($row['fieldcheck'], $v))
				{
					ShowMsg($row['fieldcback']);
					exit();
				}
			}
	
			if($row['fieldtype'] == 'datetime')
			{
				$v = GetMkTime($v);
			}
			
			if($row['fieldtype'] == 'fileall')
			{
				$vTxt = isset($_POST[$row['fieldname'].'_txt']) ? $_POST[$row['fieldname'].'_txt'] : '';
	
				if(is_array($v) &&
				   is_array($vTxt))
				{
					$vNum = count($v);
					$vTmp = '';
			
					for($i=0;$i<$vNum;$i++)
					{
						$vTmp[] = $v[$i].','.addslashes($vTxt[$i]);
					}
					
					$v = serialize($vTmp);
				}
			}
			
			if($row['fieldtype'] == 'checkbox')
			{
				@$v = implode(',',$v);
			}
	
			$fieldname  .= ", $k";
			$fieldvalue .= ", '$v'";
			$fieldstr   .= ", $k='$v'";
		}
	}


	//自动缩略图处理
	$r = $dosql->GetOne("SELECT `picwidth`,`picheight` FROM `#@__infoclass` WHERE `id`=$classid");
	if(!empty($r['picwidth']) &&
	   !empty($r['picheight']))
	{
		ImageResize(PHPMYWIND_ROOT.'/'.$picurl, $r['picwidth'], $r['picheight']);
	}


	$sql = "INSERT INTO `$tbname` (classid, parentid, parentstr, typeid, typepid, typepstr, brandid, brandpid, brandpstr, title, colorval, boldval, flag, goodsid, payfreight, weight, attrstr, marketprice, salesprice, housenum, housewarn, warnnum, integral, source, author, linkurl, keywords, description, content, picurl, picarr, hits, orderid, posttime, checkinfo {$fieldname}) VALUES ('$classid', '$parentid', '$parentstr', '$typeid', '$typepid', '$typepstr', '$brandid', '$brandpid', '$brandpstr', '$title', '$colorval', '$boldval', '$flag', '$goodsid', '$payfreight', '$weight', '$attrstr', '$marketprice', '$salesprice', '$housenum', '$housewarn', '$warnnum', '$integral', '$source', '$author', '$linkurl', '$keywords', '$description', '$content', '$picurl', '$picarr', '$hits', '$orderid', '$posttime', '$checkinfo' {$fieldvalue})";
	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}


//修改商品信息
else if($action == 'update')
{
	//栏目权限验证
	IsCategoryPriv($cid,'update');


	//初始化参数
	if(!isset($typeid))        $typeid = '-1';
	if(!isset($brandid))       $brandid = '-1';
	if(!isset($attrid))        $attrid = '';
	if(!isset($attrvalue))     $attrvalue = '';
	if(!isset($flag))          $flag   = '';
	if(!isset($picarr))        $picarr = '';
	if(!isset($rempic))        $rempic = '';
	if(!isset($remote))        $remote = '';
	if(!isset($autothumb))     $autothumb = '';
	if(!isset($autodesc))      $autodesc = '';
	if(!isset($autodescsize))  $autodescsize = '';
	if(!isset($autopage))      $autopage = '';
	if(!isset($autopagesize))  $autopagesize = '';
	$attrstr = '';


	//获取parentstr
	$row = $dosql->GetOne("SELECT `parentid` FROM `#@__infoclass` WHERE `id`=$classid");
	$parentid = $row['parentid'];

	if($parentid == 0)
	{
		$parentstr = '0,';
	}
	else
	{
		$r = $dosql->GetOne("SELECT `parentstr` FROM `#@__infoclass` WHERE `id`=$parentid");
		$parentstr = $r['parentstr'].$parentid.',';
	}


	//获取typepstr
	if($typeid != '-1')
	{
		$row = $dosql->GetOne("SELECT `parentid` FROM `#@__goodstype` WHERE `id`=$typeid");
		$typepid = $row['parentid'];
	
		if($typepid == 0)
		{
			$typepstr = '0,';
		}
		else
		{
			$r = $dosql->GetOne("SELECT `parentstr` FROM `#@__goodstype` WHERE `id`=$typepid");
			$typepstr = $r['parentstr'].$typepid.',';
		}
	}
	else
	{
		$typepid  = '-1';
		$typepstr = '';
	}
	
	
	//获取品牌brandpstr
	if($brandid != '-1')
	{
		$row = $dosql->GetOne("SELECT `parentid` FROM `#@__goodsbrand` WHERE `id`=$brandid");
		$brandpid = $row['parentid'];
		if($brandpid == 0)
		{
			$brandpstr = '0,';
		}
		else
		{
			$r = $dosql->GetOne("SELECT `parentstr` FROM `#@__goodsbrand` WHERE `id`=$brandpid");
			$brandpstr = $r['parentstr'].$brandpid.',';
		}
	}
	else
	{
		$brandpid  = '-1';
		$brandpstr = '';
	}


	//商品属性
	if(is_array($attrid) && is_array($attrvalue))
	{
		//组成商品属性与值
		$attrstr .= 'array(';
		$attrids = count($attrid);
		for($i=0; $i<$attrids; $i++)
		{
			$attrstr .= '"'.$attrid[$i].'"=>'.'"'.$attrvalue[$i].'"'; 
			if($i < $attrids-1)
			{
				$attrstr .= ',';
			}
		}
		$attrstr .= ');';
	}
	

	//文章属性
	if(is_array($flag))
	{
		$flag = implode(',',$flag);
	}


	//文章组图
	if(is_array($picarr) &&
	   is_array($picarr_txt))
	{
		$picarrNum = count($picarr);
		$picarrTmp = '';

		for($i=0;$i<$picarrNum;$i++)
		{
			$picarrTmp[] = $picarr[$i].','.$picarr_txt[$i];
		}

		$picarr = serialize($picarrTmp);
	}


	//保存远程缩略图
	if($rempic=='true' and
	   preg_match("#^http:\/\/#i", $picurl))
	{
		$picurl = GetRemPic($picurl);
	}


	//保存远程资源
	if($remote == 'true')
	{
		$content = GetContFile($content);
	}


	//第一个图片作为缩略图
	if($autothumb == 'true')
	{
		$cont_str = stripslashes($content);
		preg_match_all('/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/', $cont_str, $imgurl);

		//如果存在图片
		if(isset($imgurl[1][0]))
		{
			$picurl = $imgurl[1][0];
			$picurl = substr($picurl, strpos($picurl, 'uploads/'));
		}
	}


	//自动提取内容到摘要
	if($autodesc == 'true')
	{
		if(empty($autodescsize) or !intval($autodescsize))
		{
			$autodescsize = 200;
		}

		$descstr     = ClearHtml($content);
		$description = ReStrLen($descstr, $autodescsize);

	}


	//自动分页
    if($autopage == 'true')
    {
        $content = ContAutoPage($content, $autopagesize*1024);
    }


	$posttime = GetMkTime($posttime);


	//自定义字段处理
	$fieldname  = '';
	$fieldvalue = '';
	$fieldstr   = '';

	$ids = GetDiyFieldCatePriv('4',$classid);
	if(!empty($ids))
	{
		$dosql->Execute("SELECT * FROM `#@__diyfield` WHERE infotype=4 AND `id` IN ($ids) AND checkinfo=true ORDER BY orderid ASC");
		while($row = $dosql->GetArray())
		{
			$k = $row['fieldname'];
			if(isset($_POST[$row['fieldname']]))
			{
				if(is_array($_POST[$row['fieldname']]))
				{
					foreach($_POST[$row['fieldname']] as $post_value)
					{
						$v[] = addslashes($post_value);
					}
				}
				else
				{
					$v = addslashes($_POST[$row['fieldname']]);
				}
			}
			else
			{
				$v = '';
			}

			if(!empty($row['fieldcheck']))
			{
				if(!preg_match($row['fieldcheck'], $v))
				{
					ShowMsg($row['fieldcback']);
					exit();
				}
			}
	
			if($row['fieldtype'] == 'datetime')
			{
				$v = GetMkTime($v);
			}
			
			if($row['fieldtype'] == 'fileall')
			{
				$vTxt = isset($_POST[$row['fieldname'].'_txt']) ? $_POST[$row['fieldname'].'_txt'] : '';
	
				if(is_array($v) &&
				   is_array($vTxt))
				{
					$vNum = count($v);
					$vTmp = '';
			
					for($i=0;$i<$vNum;$i++)
					{
						$vTmp[] = $v[$i].','.addslashes($vTxt[$i]);
					}
					
					$v = serialize($vTmp);
				}
			}
			
			if($row['fieldtype'] == 'checkbox')
			{
				@$v = implode(',',$v);
			}
	
			$fieldname  .= ", $k";
			$fieldvalue .= ", '$v'";
			$fieldstr   .= ", $k='$v'";
		}
	}


	//自动缩略图处理
	$r = $dosql->GetOne("SELECT `picwidth`,`picheight` FROM `#@__infoclass` WHERE `id`=$classid");
	if(!empty($r['picwidth']) &&
	   !empty($r['picheight']))
	{
		ImageResize(PHPMYWIND_ROOT.'/'.$picurl, $r['picwidth'], $r['picheight']);
	}


	$sql = "UPDATE `$tbname` SET classid='$classid', parentid='$parentid', parentstr='$parentstr', typeid='$typeid', typepid='$typepid', typepstr='$typepstr', brandid='$brandid', brandpid='$brandpid', brandpstr='$brandpstr', title='$title', colorval='$colorval', boldval='$boldval', flag='$flag', goodsid='$goodsid', payfreight='$payfreight', weight='$weight', attrstr='$attrstr', marketprice='$marketprice', salesprice='$salesprice', housenum='$housenum', housewarn='$housewarn', warnnum='$warnnum', integral='$integral', source='$source', author='$author', linkurl='$linkurl', keywords='$keywords', description='$description', content='$content', picurl='$picurl', picarr='$picarr', hits='$hits', orderid='$orderid', posttime='$posttime', checkinfo='$checkinfo' {$fieldstr} WHERE id=$id";
	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}


//修改审核状态
else if($action == 'check')
{
	//审核权限
	$r = $dosql->GetOne("SELECT `classid` FROM `#@__goods` WHERE `id`=$id");
	IsCategoryPriv($r['classid'],'update');


	if($checkinfo == '已审')
	{
		$dosql->ExecNoneQuery("UPDATE `$tbname` SET `checkinfo`='false' WHERE `id`=$id");
		echo '<a href="javascript:;" onclick="CheckInfo('.$id.',\'未审\')" title="点击进行审核与未审操作">未审</a>';
		exit();
	}

	if($checkinfo == '未审')
	{
		$dosql->ExecNoneQuery("UPDATE `$tbname` SET `checkinfo`='true' WHERE `id`=$id");
		echo '<a href="javascript:;" onclick="CheckInfo('.$id.',\'已审\')" title="点击进行审核与未审操作">已审</a>';
		exit();
	}
}


//无状态返回
else
{
	header("location:$gourl");
	exit();
}
?>