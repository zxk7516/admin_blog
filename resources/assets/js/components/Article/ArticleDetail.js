import React, { Component } from 'react';
import { Breadcrumb, Icon, Spin, message} from 'antd';
import { Link } from 'react-router-dom';
import { ArticleForm } from './ArticleForm';
import styles from "./ArticleDetail.css"

export class ArticleDetail extends React.Component {
  constructor(props) {
    super();
    this.state = {
      //文章相关
      id:props.match.params.id,
      article:{},
      loading:true,
    };
  }
  componentDidMount(props) {
    var that = this
    //获取文章数据
    axios.get('/admin_api/articles/' + this.state.id)
    .then(function (response) {
      that.setState({
        article:response.data,
        loading:false,
      })
    })
    .catch(function (error) {
      console.log(error);
    });
  }
  handleSubmit(article) {
    console.log(article);
    var that = this
    if (article.title == '') {
      message.error('标题不能为空');
    }else {
      //更新文章
      axios.post('/admin_api/articles', {
        id:this.state.id,
        title:article.title,
        cover:article.cover,
        content:article.content,
      })
      .then(function (response) {
        console.log(response);
        if (response.status == 200) {
          message.success(response.data.message)
        }
      })
      .catch(function (error) {
        console.log(error);
      });
    }
  }
  render(){
    return (
      <div>
        <Breadcrumb style={{ marginBottom:20 }}>
          <Breadcrumb.Item>
            <Link to="/articles">
            <Icon type="home" />
            <span> 文章管理</span>
            </Link>
          </Breadcrumb.Item>
          <Breadcrumb.Item>
            文章编辑
          </Breadcrumb.Item>
        </Breadcrumb>
        <Spin spinning={this.state.loading}>
          <ArticleForm article={this.state.article} handleSubmit={this.handleSubmit.bind(this)}/>
        </Spin>
      </div>
    )
  }
}
