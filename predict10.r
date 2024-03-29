library("neuralnet")
library(rjson)

#Set working directory for the training data
#setwd("C:/OpenServer/domains/localhost/")
#getwd()

#������ ������ ��� �������� ���������
mydata<- read.csv(file="C:/Android/project/neural2/data1.csv", header=TRUE, sep=";")
#mydata<- read.csv(file="data.csv", header=TRUE, sep=";")
mydata
attach(mydata)
names(mydata)
#print (mydata)

#��������� �������������� ���������
predict<- read.csv(file="C:/Android/project/neural2/predict1.csv", header=FALSE, sep=";")
#predict<- read.csv(file="predict.csv", header=FALSE, sep=";")
predict
attach(predict)
names(predict)
#print (predict)

#������� ���������� �������������� ����������
count<- nrow(predict)
#print (count)

#������� ���������
#��������� ����� 10 ����
#Threshold is a numeric value specifying the threshold for the partial
#derivatives of the error function as stopping criteria.
net.sqr <- neuralnet(OUTPUT~INPUT,mydata, hidden=10, threshold=0.01)
#net.sqr <- neuralnet(output ~ input, data.norm, hidden=10, threshold=0.01)

#print(net.sqr)
 
#������ ���������
#plot(net.sqr)
 
#���������������
testdata <- as.data.frame(predict) #������� �������������� ��������� � ����������
#colnames(testdata)<-c("INPUT")
net.results <- compute(net.sqr, testdata) #��������� �������� ������� �� ��������� �������������� ����������
#net.results <- predict(net.sqr, testdata) 
#Lets see what properties net.sqr has
#ls(net.results)
 
#Lets see the results
#print(net.results$net.result)
 
#Lets display a better version of the results
cleanoutput <- cbind(testdata,testdata,
                         as.data.frame(net.results$net.result))
colnames(cleanoutput) <- c("Input","Expected Output","Neural Net Output")
#print(cleanoutput)

cat(toJSON(net.results$net.result))
