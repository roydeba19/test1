var express = require('express');
var app = express();
const base_uri = 'http://localhost:3000/';
const PORT = 3000;

//var router = express.Router();
// var movies = [
//    {id: 101, name: "Fight Club 123", year: 1999, rating: 8.1},
//    {id: 102, name: "Inception", year: 2010, rating: 8.7},
//    {id: 103, name: "The Dark Knight", year: 2008, rating: 9},
//    {id: 104, name: "12 Angry Men", year: 1957, rating: 8.9}
// ];
// app.get('/', function(req, res){    
//   res.json(movies);
// });

var mongoose = require('mongoose');
const db_uri = 'mongodb://localhost:27017/xyz_db';
const options = {
  useNewUrlParser: true,
  // autoIndex: false, // Don't build indexes
  // reconnectTries: Number.MAX_VALUE, // Never stop trying to reconnect
  // reconnectInterval: 500, // Reconnect every 500ms
  // poolSize: 10, // Maintain up to 10 socket connections
  // // If not connected, return errors immediately rather than waiting for reconnect
  // bufferMaxEntries: 0,
  // connectTimeoutMS: 10000, // Give up initial connection after 10 seconds
  // socketTimeoutMS: 45000, // Close sockets after 45 seconds of inactivity
  // family: 4 // Use IPv4, skip trying IPv6
};

mongoose.connect(db_uri,options);
const cors = require('cors');

var multer = require('multer');
var upload = multer({dest:'uploads/'});

//var bodyParser = require('body-parser');
//app.use(bodyParser.urlencoded({extended:false}));
//app.use(bodyParser.json());
const formidableMiddleware = require('express-formidable');
// app.use(formidableMiddleware({
//   encoding: 'utf-8',
//   uploadDir: 'uploads/',
//   keepExtensions:true
// }));
app.use(cors());
//app.use(multer);

var Product = require('./models/Product');  


app.post('/create',formidableMiddleware({
  encoding: 'utf-8',
  uploadDir: 'uploads/',
  keepExtensions:true
}),function(req,res,next){
  
  var product = new Product();
  product.name = req.fields.productName;
  product.price = req.fields.productPrice;
  product.image = req.files.productImage.name;


product.save(function (err,result) {
    if (err) return handleError(err);
    else
      res.json(result).status(200);
    // saved!
  });
});

app.post('/update',function(req,res){
  
  //var product = new Product();   
  var query = { _id: req.body._id };
  var proObj = {
    'name':req.body.name,
    'price':req.body.price
  };

  Product.findOneAndUpdate({
    _id: req.body._id
  },{$set:proObj},{new: true},function(err, result) {
  if (err) return handleError(err);
    else
      res.json(result).status(200);
  });
});

app.get('/list',function(req,res){
  res.header("Access-Control-Allow-Origin", "*");
  Product.find(function(err,result){
    if (err) return handleError(err);
    else
      res.json(result).status(200);
  });
});



app.listen(PORT);