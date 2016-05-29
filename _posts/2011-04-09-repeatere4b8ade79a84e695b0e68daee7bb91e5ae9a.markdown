---
author: me115wp
comments: true
date: 2011-04-09 10:27:15+00:00
layout: post
link: http://blog.me115.com/2011/04/143
slug: repeater%e4%b8%ad%e7%9a%84%e6%95%b0%e6%8d%ae%e7%bb%91%e5%ae%9a
title: Repeater中的数据绑定
wordpress_id: 143
categories:
- WEB开发
tags:
- Repeater
---

**_Repeater基础_**

 

在aspx文件中加入Repeater 控件，在<ItemTemplate></ItemTemplate>包含的范围里加入自己控制的代码，需要替换的变量使用<%# Eval("SellerName")%>；注意两侧的引号。

 

.aspx:

 
    
    <asp:Repeater ID="SellerRpt" runat="server">
        <ItemTemplate>
            <li><a href='<%# Eval("SellerName")%>' target="_blank">
                <%# Eval("ComName")%></a></li>
        </ItemTemplate>
    </asp:Repeater>





对应的后台cs中，在页面加载处加入数据绑定的代码：




    
    protected void Page_Load(object sender, EventArgs e)
    {
        if (!IsPostBack)
        {
            DataTable dt = SellerDA.GetTopHotSellers(9);
            SellerRpt.DataSource = dt;
            SellerRpt.DataBind();
        }
    }





aspx中"SellerName"、"ComName"为DataTable 中的列名。









**_优化_**





直接使用DataItem可减少Eval函数的执行步骤，优化页面解析时间：





<%# ((DataRowView)Container.DataItem)["SellerName"]%>替换<%# Eval("SellerName")%>









**_ArrayList数据源_**





如果数据源是ArrayList，并且ArrayList为一列string数组，则可不用写出列名：





.aspx:




    
    <asp:Repeater ID="topAdHintRpt" runat="server">
        <ItemTemplate>
            <asp:Label ID="BarLabel" CssClass="bar" runat="server" Text="|"></asp:Label>
            <a href="#"><span>
                <%#Container.DataItem%></span></a>
        </ItemTemplate>
    </asp:Repeater>





.cs:




    
            ArrayList alterText;
            AdDA.GetIndexTopList(out alterText);
            topAdHintRpt.DataSource = alterText;
            topAdHintRpt.DataBind();









**_处理后显示_**





某些情况下，数据库中检索出来的数据并不适合直接显示出来，想要适当处理后显示（eg：日期的格式，字符串长度的控制），可使用标签来占位,在onitemdatabound函数中自行控制：





.aspx:




    
    <asp:Repeater ID="Repeater1" runat="server" OnItemDataBound="ProRpt_ItemDataBound">
        <ItemTemplate>
            <asp:Label ID="colinDate" runat="server" Text=""></asp:Label>
        </ItemTemplate>
    </asp:Repeater>





.cs:




    
        protected void ProRpt_ItemDataBound(object sender, RepeaterItemEventArgs e)
        {
            if (e.Item.ItemType == ListItemType.Item || e.Item.ItemType == ListItemType.AlternatingItem)
            {
                DataRowView rowv = (DataRowView)e.Item.DataItem;//找到分类Repeater关联的数据项 
                string strDate = rowv["clDate"].ToString();
                Label DateLB = e.Item.FindControl("colinDate") as Label;
                DateLB.Text = strDate.Substring(0, 10);
            }
        }









**_嵌套Reapeter的显示_**





对于某些复杂的显示逻辑，需用用到Reapeter的嵌套，这里需要自行控制2层数据源的数据绑定：





.aspx:




    
    <asp:Repeater ID="Repeater1" runat="server" OnItemDataBound="ProRpt_ItemDataBound">
        <ItemTemplate>
            <asp:Repeater ID="ParaRpt" runat="server" OnItemDataBound="ParaRpt_ItemDataBound">
                <ItemTemplate>
                    <asp:Label ID="bar" CssClass="bar" runat="server" Text="|"></asp:Label>
                    <span class="para">
                        <%# Eval("Name")%>:
                        <%# Eval("Value")%></span>
                </ItemTemplate>
            </asp:Repeater>
        </ItemTemplate>
    </asp:Repeater>





.cs:




    
        protected void ProRpt_ItemDataBound(object sender, RepeaterItemEventArgs e)
        {
            //判断里层repeater处于外层repeater的哪个位置（ AlternatingItemTemplate，FooterTemplate，
            //HeaderTemplate，，ItemTemplate，SeparatorTemplate
            if (e.Item.ItemType == ListItemType.Item || e.Item.ItemType == ListItemType.AlternatingItem)
            {
                Repeater rep = e.Item.FindControl("ParaRpt") as Repeater;//找到里层的repeater对象
                DataRowView rowv = (DataRowView)e.Item.DataItem;//找到分类Repeater关联的数据项 
                string str = Convert.ToString(rowv["Pro_Content"]); //获取填充子类的内容
                rep.DataSource = Product.FillPara(str);
                rep.DataBind();
            }
        }
