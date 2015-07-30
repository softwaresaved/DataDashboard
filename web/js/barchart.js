function drawbar (incomingdata) {

var margin = {top: 20, right: 30, bottom: 30, left: 40},
    width = 960 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom;

var barWidth = width / incomingdata.length;

var y = d3.scale.linear()
    .range([height, 0]);

/*var x = d3.scale.linear()
    .domain([0, d3.max(incomingdata)])
    .range([0, 420]);
*/

var chart  = d3.select("#chart")
     .attr("width", width)
     .attr("height", height)
    /*.selectAll("div")
    .data(incomingdata)
    .enter().append("div")
    //.style("width", function(d) { return x(d.value) + "px"; })
    //.text(function(d) { return d.value; });
*/
y.domain([0, d3.max(incomingdata, function(d) { return d.value; })]);

var bar = chart.selectAll("div")
         .data(incomingdata)
         .enter().append("g")
         .attr("transform", function(d, i) { return "translate(" + i * barWidth + ",0)"; });
 
 bar.append("rect")
      .attr("y", function(d) { return d.value; })
      .attr("height", function(d) { console.log(d.value); return d.value; })
      .attr("width", barWidth - 1);

 bar.append("text")
      .attr("x", barWidth/2)
      .attr("y", function(d) { return d.value; })
      .attr("dy", "0.75em")
      .text(function(d) { return d.value; });
}
