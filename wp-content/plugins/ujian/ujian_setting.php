<style type="text/css">
    #ujianPluginLogo {
        background: url("http://www.ujian.cc/resource/default/images/wp_plugin_logo.gif") no-repeat;
        width: 85px;
        float: left;
        height: 39px;
        margin: 14px 6px 0 0;
    }
    
    #ujianPostboxContainer {
        margin-bottom: 5px;
        width: 100%;
    }
    
    .ujian_metabox_holder {
        padding-top: 10px;
    }
    
    #ujianPostboxContainer .ujian_sortable {
        min-height: 0;
        height: 100%;
    }
    
    .ujian_postbox {
        margin-bottom: 0;
    }
    
    .ujian_postbox h3 {
        background-color: #E0ECF6;
        color: #093E56;
        font-size: 12px;
        font-weight: bold;
        line-height: 1;
        margin: 0;
        padding: 7px 9px;
        text-shadow: 0 1px 0 #FFFFFF;
    }
    
    .wrap .ujian_plugin_title {
        font: 24px/35px Georgia,"Times New Roman","Bitstream Charter",Times,serif,"Microsoft YaHei Bold","Microsoft YaHei","微软雅黑","WenQuanYi Zen Hei","文泉驿正黑","WenQuanYi Micro Hei","文泉驿微米黑","黑体";
        color: #093E56;
        margin: 0;
        padding: 14px 15px 3px 0;
        clear: none;
    }
    
    .ujian_postbox_inside {
        margin: 10px;
        position: relative;
    }
    
    .ujian_submit {
        padding: 0;
        border-top: none;
    }
    
    .ujian_postbox_inside ul {
        padding-left: 0;
        margin-left: 0;
    }
    
    .ujian_postbox_inside li {
        margin: 15px 0;
        overflow: hidden;
        line-height: 25px;
        list-style-type: none;
    }
    
    .ujian_option_name {
        float: left;
        font-weight: bold;
        width: 180px;
    }
    
    .ujian_options {
        float: left;
    }
    
    .ujian_option_tip {
        color: #707070;
    }
    
    .ujian_option_tip a {
        color: #707070;
        font-size: 11px;
    }
    
    #ujianPostboxContainer .ujian_add_verification_meta_bt {
        background: orange none;
        border-color: #D28A03;
        text-shadow: 0 1px 0 rgba(0, 0, 0, 0.3)
    }
    
    .ujian_postbox_inside iframe {
        margin: 6px 0;
    }
.btnr{ display:block; float:left; margin-right:8px; margin-top:18px;}
.choose_btn{ width:70px; overflow:hidden; background:none; height:32px; margin-right:40px; margin-top:8px;float:left;}
.blue_ujian{ background:url(http://img.ujian.cc/default/1.png) no-repeat; width:32px; height:32px; overflow:hidden; display:block; float:left; margin-right:4px;}
.blue_top{ background:url(http://img.ujian.cc/default/1.png) no-repeat -64px 0; width:32px; height:32px; overflow:hidden; display:block; float:left;}
.blue_ujian_hover{ background:url(http://img.ujian.cc/default/1.png) no-repeat -32px 0; width:32px; height:32px; overflow:hidden; display:block; float:left; margin-right:4px;}
.blue_top_hover{ background:url(http://img.ujian.cc/default/1.png) no-repeat -96px 0; width:32px; height:32px; overflow:hidden; display:block; float:left;}
.orange_ujian{ background:url(http://img.ujian.cc/default/2.png) no-repeat; width:32px; height:32px; overflow:hidden; display:block; float:left; margin-right:4px;}
.orange_top{ background:url(http://img.ujian.cc/default/2.png) no-repeat -64px 0; width:32px; height:32px; overflow:hidden; display:block; float:left;}
.orange_ujian_hover{ background:url(http://img.ujian.cc/default/2.png) no-repeat -32px 0; width:32px; height:32px; overflow:hidden; display:block; float:left; margin-right:4px;}
.orange_top_hover{ background:url(http://img.ujian.cc/default/2.png) no-repeat -96px 0; width:32px; height:32px; overflow:hidden; display:block; float:left;}
.gray_ujian{ background:url(http://img.ujian.cc/default/3.png) no-repeat; width:32px; height:32px; overflow:hidden; display:block; float:left; margin-right:4px;}
.gray_top{ background:url(http://img.ujian.cc/default/3.png) no-repeat -64px 0; width:32px; height:32px; overflow:hidden; display:block; float:left;}
.gray_ujian_hover{ background:url(http://img.ujian.cc/default/3.png) no-repeat -32px 0; width:32px; height:32px; overflow:hidden; display:block; float:left; margin-right:4px;}
.gray_top_hover{ background:url(http://img.ujian.cc/default/3.png) no-repeat -96px 0; width:32px; height:32px; overflow:hidden; display:block; float:left;}
.green_ujian{ background:url(http://img.ujian.cc/default/4.png) no-repeat; width:32px; height:32px; overflow:hidden; display:block; float:left; margin-right:4px;}
.green_top{ background:url(http://img.ujian.cc/default/4.png) no-repeat -64px 0; width:32px; height:32px; overflow:hidden; display:block; float:left;}
.green_ujian_hover{ background:url(http://img.ujian.cc/default/4.png) no-repeat -32px 0; width:32px; height:32px; overflow:hidden; display:block; float:left; margin-right:4px;}
.green_top_hover{ background:url(http://img.ujian.cc/default/4.png) no-repeat -96px 0; width:32px; height:32px; overflow:hidden; display:block; float:left;}
	
</style>
<div class="wrap">
   <a href="http://www.ujian.cc/" target="_blank"><div id="ujianPluginLogo" class="icon32"></div></a>
    <h2 class="ujian_plugin_title">友荐相关文章插件</h2>
    <div id="ujianPostboxContainer" class="postbox-container">
        <div class="metabox-holder ujian_metabox_holder">
            <div class="meta-box-sortables ui-sortable ujian_sortable">
            <?php if($updated){echo '<div class="updated"><p>插件更新成功</p></div>';}?>
                <form method="post"  action="" name="ujian_setting" id="ujian_setting">
                    <div class="postbox ujian_postbox">
                        <h3 class="hndle"><span>基本设置</span></h3>
                        <div class="ujian_postbox_inside">
                        		<ul>
                                <li>
                                    <span class="ujian_option_name">最多显示推荐条目：</span>
                                    <div class="ujian_options">
                                        <select name="ujian_view_num">
                                          <?php for($i=1;$i<=10;$i++){
												  ?>
                                             <option value='<?php echo $i;?>' <?php if(get_option('ujian_view_num')== $i){echo 'selected';}?> > <?php echo  $i?> </option>
                                             <?php
										  }?>
                                          
                                        </select>
                                    </div>
                                        <span class="ujian_option_tip" style="margin-left:279px;">设置显示个数</span>
                                </li>
                               
                                 <li>
                                    <span class="ujian_option_name">打开方式：</span>
                                    <div class="ujian_options">
                                        <input type="radio" name="ujian_target" value="0" <?php if(get_option('ujian_target') =='0') echo 'checked'?>/>当前窗口
                                        <input type="radio" name="ujian_target" value="1" <?php if(get_option('ujian_target') =='1') echo 'checked'?>/>新窗口
                                    </div>
                                    <span class="ujian_option_tip"  style="margin-left:200px;">选择推荐文章的打开方式，默认是当前窗口。</span>
                                </li>
                                <li>
                                    <span class="ujian_option_name">填写您在友荐的UID：</span>
                                    <div class="ujian_options">
									<input type="text" name="ujian_uid" value="<?php echo get_option('ujian_uid')?>" />
                                    </div>
                                    <span class="ujian_option_tip"  style="margin-left:184px;">填写您在友荐注册的UID，用于数据统计和收录管理</span>
                                </li>
								
                                <li>
                                    <span class="ujian_option_name">设置文字颜色：</span>
                                    <div class="ujian_options">
									<input type="text" name="ujian_textcolor" value="<?php echo get_option('ujian_textcolor')?>" />
                                    </div>
                                    <span class="ujian_option_tip"  style="margin-left:184px;">文字颜色设置，可以是CSS中color属性支持的任意值，(如#FFF或者orange)。默认的颜色是#333333</span>
                                </li>
                                
                                    <li>
                                    <span class="ujian_option_name">设置文字悬浮颜色：</span>
                                    <div class="ujian_options">
									<input type="text" name="ujian_hovertextcolor" value="<?php echo get_option('ujian_hovertextcolor')?>" />
                                    </div>
                                    <span class="ujian_option_tip"  style="margin-left:178px;">鼠标滑过时候的文字颜色，可以是CSS中color属性支持的任意值，(如#FFF或者orange)。 默认值#333333 </span>
                                </li>

                                <li>
                                    <span class="ujian_option_name">鼠标滑过背景：</span>
                                    <div class="ujian_options">
									<input type="text" name="ujian_mouseovercolor" value="<?php echo get_option('ujian_mouseovercolor')?>" />
                                    </div>
                                    <span class="ujian_option_tip"  style="margin-left:184px;">鼠标滑过时候的背景色,默认是 #E6F3DE</span>
                                </li>
                                <li>
                                    <span class="ujian_option_name">设置边框颜色：</span>
                                    <div class="ujian_options">
									<input type="text" name="ujian_bordercolor" value="<?php echo get_option('ujian_bordercolor')?>" />
                                    </div>
                                    <span class="ujian_option_tip"  style="margin-left:184px;">边框颜色设置，可以是CSS中color属性支持的任意值，(如#FFF或者orange)。默认的颜色是#dddddd</span>
                                </li>
                                <li>
                                    <span class="ujian_option_name">默认显示图片：</span>
                                    <div class="ujian_options">
									<input type="text" name="ujian_defaultpic" value="<?php echo get_option('ujian_defaultpic')?>" />
                                    </div>
                                    <span class="ujian_option_tip"  style="margin-left:184px;">设置没有图片文章的默认图片，留空表示智能匹配。</span>
                                </li>
                                
                                 <li>
                                    <span class="ujian_option_name">代码风格：</span>
                                    <div class="ujian_options" id="ujian_codestyle">
                                    <?php 
										$ujian_codestyle = get_option('ujian_codestyle');
										$style_arr = array_filter(explode(',',$ujian_codestyle));
										
										
									?>
                                        <input type="checkbox" name="ujian_codestyle_widge" onclick="show_ujianstyle(this)" value="widge"  <?php if(in_array('widge',$style_arr)) echo 'checked'?> />嵌入式
                                        <input type="checkbox" name="ujian_codestyle_slide" onclick="show_ujianstyle(this)" value="slide"  <?php if(in_array('slide',$style_arr)) echo 'checked'?>/>侧栏式
                                    </div>
                                    <span class="ujian_option_tip"  style="margin-left:210px;">设置您的代码风格</span>
								</li>  
                                <!--  嵌入式-->
                                <div id="ujian_codestyle_widge"  style="display:<?php if (in_array('widge',$style_arr))echo 'block';else echo 'none'; ?>;">
                                 <li>
                                    <span class="ujian_option_name">显示方式：</span>
                                    <div class="ujian_options">
                                       <input type="radio" name="ujian_showtype" value="0"  <?php if(get_option('ujian_showtype') =='0') echo 'checked'?> />智能方式
                                        <input type="radio" name="ujian_showtype" value="1"   <?php if(get_option('ujian_showtype') =='1') echo 'checked'?>/>文字方式
                                        <input type="radio" name="ujian_showtype" value="2"   <?php if(get_option('ujian_showtype') =='2') echo 'checked'?>/>图文方式（默认）
                                        
                                    </div>
                                    <span class="ujian_option_tip" style="margin-left:69px;">选择嵌入式显示方式</span>
                                </li>
                                 <li>
                                    <span class="ujian_option_name">启用自定义显示位置：</span>
                                    <div class="ujian_options">
                                        <input type="radio" name="ujian_define_pos" value="yes" <?php if(get_option('ujian_define_pos') =='yes') echo 'checked'?>/>YES
                                        <input type="radio" name="ujian_define_pos" value="no" <?php if(get_option('ujian_define_pos') =='no') echo 'checked'?>/>NO
                                    </div>
                                    <span class="ujian_option_tip"  style="margin-left:252px;">启用后，拷贝<code>&lt;div class="ujian-hook"&gt;&lt;/div&gt;</code>在single.php页面添加到你想要显示的地方。</span>
                                </li>   
                                <li>
                                    <span class="ujian_option_name">默认图片大小：</span>
                                    <div class="ujian_options">
									<input type="text" name="ujian_picsize" value="<?php echo get_option('ujian_picsize')?>" />
                                    </div>
                                    <span class="ujian_option_tip"  style="margin-left:184px;">图片模式的图片大小，80～120,默认为96,单位:px，这里直接填写数字就可以</span>
                                </li>
                                <li>
                                    <span class="ujian_option_name">设置背景色：</span>
                                    <div class="ujian_options">
									<input type="text" name="ujian_bgcolor" value="<?php echo get_option('ujian_bgcolor')?>" />
                                    </div>
                                    <span class="ujian_option_tip"  style="margin-left:184px;">可以是CSS中color属性支持的任意值，(如#FFF或者orange)。默认的颜色是网页的背景色。</span>
                                </li>
                                <li>
                                    <span class="ujian_option_name">模块显示的名称：</span>
                                    <div class="ujian_options">
									<input type="text" name="ujian_itemtitle" value="<?php echo get_option('ujian_itemtitle')?>" />
                                    </div>
                                    <span class="ujian_option_tip" style="margin-left:184px;">默认为"您可能也喜欢："</span>
                                </li>
                                </div>
                                <!-- 浮窗口-->
                                <div id="ujian_codestyle_slide" style="display:<?php if (in_array('slide',$style_arr))echo 'block';else echo 'none'; ?>;">
                                <li>
                                    <span class="ujian_option_name">页面显示模式：</span>
                                    <div class="ujian_options">
                                       <input type="radio" name="ujian_view_mode" value="single" <?php if(get_option('ujian_view_mode') =='single') echo 'checked'?> />文章详细页面显示
                                        <input type="radio" name="ujian_view_mode" value="all"   <?php if(get_option('ujian_view_mode') =='all') echo 'checked'?>/>全站显示
                                         <input type="checkbox" name="ujian_page_view" value="yes"   <?php if(get_option('ujian_page_view') =='yes') echo 'checked'?>/> 单页面显示
                                    </div>
                                    <span class="ujian_option_tip" style="margin-left:60px;">设置侧栏式的页面显示模式</span>

                                </li>
                                <li>
                                    <span class="ujian_option_name">侧栏式按钮位置：</span>
                                    <div class="ujian_options">
                                        <input type="radio" name="ujian_slide_pos" value="left" <?php if(get_option('ujian_slide_pos') =='left') echo 'checked'?>/>左侧
                                        <input type="radio" name="ujian_slide_pos" value="right" <?php if(get_option('ujian_slide_pos') =='right') echo 'checked'?>/>右侧
                                    </div>
                                    
                                    <span class="ujian_option_tip" style=" margin-left:237px;">设置侧栏式显示位置</span>
                                </li>
                                <li>
<div class="indexlist2">
        <div class="listtitle f14"><strong>按钮风格选择：</strong></div>
	
			<input class="btnr"	 name="ujian_icon" type="radio" value="1" id="icon1" <?php if(get_option('ujian_icon') =='1') echo 'checked'?>/>
            <a href="javascript:;" class="choose_btn" onclick="checked_this('icon1')">
            <span class="blue_ujian" onmouseover="changeStyle(this,'blue_ujian_hover');" onmouseout="changeStyle(this,'blue_ujian');"></span>
            <span class="blue_top" onmouseover="changeStyle(this,'blue_top_hover');" onmouseout="changeStyle(this,'blue_top');"></span>
            </a>
            <input class="btnr" name="ujian_icon"  type="radio" value="2" id="icon2"  <?php if(get_option('ujian_icon') =='2') echo 'checked'?>/>
            <a href="javascript:;" class="choose_btn" onclick="checked_this('icon2')">
            <span class="orange_ujian" onmouseover="changeStyle(this,'orange_ujian_hover');" onmouseout="changeStyle(this,'orange_ujian');"></span>
            <span class="orange_top" onmouseover="changeStyle(this,'orange_top_hover');" onmouseout="changeStyle(this,'orange_top');"></span>
            </a>
            <input class="btnr"  name="ujian_icon" type="radio" value="3" id="icon3"  <?php if(get_option('ujian_icon') =='3') echo 'checked'?>/>
            <a href="javascript:;" class="choose_btn" onclick="checked_this('icon3')" >
            <span class="gray_ujian" onmouseover="changeStyle(this,'gray_ujian_hover');" onmouseout="changeStyle(this,'gray_ujian');"></span>
            <span class="gray_top" onmouseover="changeStyle(this,'gray_top_hover');" onmouseout="changeStyle(this,'gray_top');"></span>
            </a>
            <input class="btnr" name="ujian_icon" type="radio" value="4" id="icon4"  <?php if(get_option('ujian_icon') =='4') echo 'checked'?>/>
            <a href="javascript:;" class="choose_btn" onclick="checked_this('icon4')">
            <span class="green_ujian" onmouseover="changeStyle(this,'green_ujian_hover');" onmouseout="changeStyle(this,'green_ujian');"></span>
            <span class="green_top" onmouseover="changeStyle(this,'green_top_hover');" onmouseout="changeStyle(this,'green_top');"></span>
            </a>
<script>
function changeStyle(o,class_name){
	var A = (typeof(o) =="object") ? o : getObj(o);
	A.className = class_name;
}
function getObj(objectId) {
	if(document.getElementById && document.getElementById(objectId)) {// W3C DOM
		return document.getElementById(objectId);
	} else if (document.all && document.all(objectId)) {// MSIE 4 DOM
		return document.all(objectId);
	} else if (document.layers && document.layers[objectId]) {// NN 4 DOM. note: this won't find nested layers
		return document.layers[objectId];
	} else {
		return false;
	}
}
function checked_this(obj){
	document.getElementById(obj).checked=true;
}


function show_ujianstyle(obj){
	var d = document.getElementById("ujian_codestyle");
	var input = d.getElementsByTagName("input");
	var str = '';
	if(obj.checked == true){
		document.getElementById("ujian_codestyle_"+obj.value).style.display="block";
	}else{
		document.getElementById("ujian_codestyle_"+obj.value).style.display="none";
	}
	for(i=0;i<input.length;i++){
		str += input[i].checked;
	}
	if(str.indexOf('true') < 0){
		var index = (obj.value)-1
		obj.checked = true;
		document.getElementById("ujian_codestyle_"+obj.value).style.display='block'
		alert('必须要选中一项！');
	}
	
	
}

</script>			
       <div class="clear"></div>
      </div>                          
        </li>
                                </div>
                                </ul>
                        </div>
                    </div>
                    <div class="submit">
                    <input type="hidden" name="updatesubmit" value="1" />
                        <input class="button-primary" type="submit" value="更新设置" />
                        <span>注：刚装上插件以后可能需要一些时间才能看到相关文章，友荐已经在获取您博客的文章信息。</span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>  
 