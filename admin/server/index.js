const express = require('express');
const app = express();
const categoriesRouter = require('./routes/categories');
const postRouter = require('./routes/post');
const userRoute = require('./routes/user');
const bodyParser = require("body-parser");
const cors = require("cors");

app.use(cors());
app.use(express.json());
app.use(bodyParser.urlencoded({extended : true}));

app.get("/",(req,res)=>{
    res.send("This is Home!");
});

app.use("/categories", categoriesRouter);
app.use("/post", postRouter);
app.use("/user",  userRoute);



app.use((err, req, res, next) => {
    const statusCode = err.statusCode || 500;

    console.error(err.message, err.stack);

    res.status(statusCode).json({ message : err.message });
    return;
});

app.listen(5000,()=>{
    console.log(`Listening on port 5000`);
});