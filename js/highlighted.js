// JavaScript Document

function changeHighlightedState(dom){
	//alert (dom);
	$("h3", dom).css ('background-image', 'url(/annanovas/images/solution_top-hover.gif)');
}

function changeNormalState(dom){
	//alert (dom);
	$("h3", dom).css ('background-image', 'url(/annanovas/images/solution_top.gif)');
}