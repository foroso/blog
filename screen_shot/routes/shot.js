const express = require('express') 
const bodyParser = require('body-parser');
const app = express(); 
const puppeteer = require('puppeteer'); 
const devices = require('puppeteer/DeviceDescriptors');
const http = require('http');
var router = express.Router();

let page; 

//项目配置项
app.set('port', process.env.PORT || 6001);
app.use(bodyParser.urlencoded({extended: false}));
app.use(bodyParser.json());

// const fs= require('fs');

//启动浏览器
(async () => { 
  browser = await puppeteer.launch({headless:true, timeout: 15000, args: ['--no-sandbox', '--disable-setuid-sandbox']}); 
})()


//接收post请求
router.post('/', function (req, res) {
  
  try{

    //输出时间
    var t1 = new Date().getTime();
    console.log("Date Time : ", new Date().toLocaleString());
    console.log("params is : ", req.body)
  
    //请求参数
    var url = req.body.url; 
    var filename = req.body.filename; 
    var img_width = +req.body.width; 
    var img_height = +req.body.height; 
    
    //图片路径
    // var image_name = '/home/public/' + generateUUID() + '.jpg';
    // var image_name = 'public/' + generateUUID() + '.jpg';
    var image_name = filename;
    
    var vocabulary=req.params.vocabulary; 

      (async() => {

        //创建页面
        page = await browser.newPage(); 

        //设置页面宽高
        await page.setViewport({
          width: img_width,
          height: img_height
        });

        //不使用缓存
        await page.setCacheEnabled(false);

        //打开页面
        var resp = await page.goto(url,{
          waitUntil: 'networkidle2'
        });

        //截图
        await page.screenshot({
          path: image_name,
          fullPage: true
        });

        //输出执行时间和文件路径
        var t2 = new Date().getTime();
        interval = (t2 - t1)/1000;
        var exec_time = 'Execute Time: ' + interval;
        console.log(exec_time);
        console.log('Save Path: ' + image_name);

        //响应客户端信息
        await page.close();
        await res.send(image_name);

      })();
  
  }
  
  //捕获异常
  catch(e)
  {
    console.log('[Error]'+e.message+'happen'+e.lineNumber+'line');
    page.close();
  }

})


module.exports = router;


// http.createServer(app).listen(app.get('port'), function(){
//   console.log('Express server listening on port ' + app.get('port'));
// });

//生成唯一图片名称
function generateUUID() {
	var d = new Date().getTime();
	var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
	var r = (d + Math.random()*16)%16 | 0;
	d = Math.floor(d/16);
	return (c=='x' ? r : (r&0x3|0x8)).toString(16);
	});
	return uuid;
    };
