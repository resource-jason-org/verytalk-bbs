<?php if (!defined('THINK_PATH')) exit();?> <html>
 <div>
     <span>新增随机文章</span></br></br>
	 <form action="<?php echo U('admin/doinsert');?>" method="post">
              <span style="margin:0 45">type</span> 
                    <select name='type'>
						<option value="1">随机回复</option>
						<option value="2">随机帖子</option>
						<option value="3">情感专题</option>
						<option value="4">幽默笑话</option>
						<option value="5">长篇别论</option>
						<option value="6">其他</option>
					</select></br></br>
		<span style="margin:0 35">subject   </span> <input type="text" name="subject"/></br></br>
		<span style="margin:0 31">message   </span> <textarea name="message" clos="20" rows="5">
	    
		 </textarea></br></br>
		 <input type="submit" value="确认"/>
	 </form>
 </div>
 </html>