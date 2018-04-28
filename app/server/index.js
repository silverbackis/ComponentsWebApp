import express from 'express'
import { Nuxt, Builder } from 'nuxt'
import bodyParser from 'body-parser'
import cookieParser from 'cookie-parser'
import helmet from 'helmet'
import AuthRoutes from '../.nuxt/bwstarter/auth_routes'
import session from 'express-session'
import MysqlSession from 'express-mysql-session'
import mysql from 'mysql'
import config from '../nuxt.config.js'
config.dev = process.env.NODE_ENV !== 'production'

const MySQLStore = MysqlSession(session)
const mysqlOps = {
  host: 'mysql',
  port: 3306,
  user: process.env.MYSQL_USER,
  password: process.env.MYSQL_PASSWORD,
  database: process.env.MYSQL_DATABASE
}
const connection = mysql.createConnection(mysqlOps)
const sessionStore = new MySQLStore({}, connection)
let sessOps = {
  secret: process.env.COOKIE_SECRET,
  name: 'JS_SESSION',
  resave: false,
  saveUninitialized: false,
  proxy: true,
  cookie: {
    secure: !config.dev,
    httpOnly: true,
    maxAge: null,
    path: '/'
  },
  store: sessionStore
}

const app = express()
const host = process.env.HOST || '127.0.0.1'
const port = process.env.PORT || 3000

app.set('port', port)
app.disable('x-powered-by')
app.set('trust proxy', !!config.dev)
app.use(bodyParser.urlencoded({ extended: false }))
app.use(bodyParser.json())
app.use(session(sessOps))
app.use(helmet())
app.use(cookieParser())

const router = express.Router()
router.use((req, res, next) => {
  Object.setPrototypeOf(req, app.request)
  Object.setPrototypeOf(res, app.response)
  req.res = res
  res.req = req
  next()
})
app.use(AuthRoutes(router))

// Init Nuxt.js
const nuxt = new Nuxt(config)

// Build only in dev mode
if (config.dev) {
  const builder = new Builder(nuxt)
  builder.build()
}

// Give nuxt middleware to express
app.use(nuxt.render)

// Listen the server
app.listen(port, host)
console.log('Server listening on ' + host + ':' + port) // eslint-disable-line no-console
