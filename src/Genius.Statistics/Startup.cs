using System;
using Genius.Statistics.Data.Contexts;
using Genius.Statistics.Services;
using Microsoft.AspNetCore.Builder;
using Microsoft.AspNetCore.Hosting;
using Microsoft.AspNetCore.Http;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.DependencyInjection;
using Microsoft.Extensions.Hosting;

namespace Genius.Statistics;

public class Startup
{
    public const string DatabaseName = "GeniusStatistics.db";

    public string DbStatisticsPath { get; internal set; }

    public Startup(IConfiguration configuration)
    {
        SetupDatabase(configuration);
    }

    public void SetupDatabase(IConfiguration configuration)
    {
        string expertDatabasePath = configuration.GetConnectionString("StatisticsDatabase");

        if (String.IsNullOrEmpty(expertDatabasePath))
        {
            var folder = Environment.SpecialFolder.LocalApplicationData;
            var path = Environment.GetFolderPath(folder);

            expertDatabasePath = System.IO.Path.Join(path, DatabaseName);
        }

        DbStatisticsPath = expertDatabasePath;
    }

    // This method gets called by the runtime. Use this method to add services to the container.
    // For more information on how to configure your application, visit https://go.microsoft.com/fwlink/?LinkID=398940
    public void ConfigureServices(IServiceCollection services)
    {
        services.AddDbContext<StatisticsContext>(options =>
        {
            options.UseSqlite($"Data Source={DbStatisticsPath}");
        });

        services.AddGrpc();
    }

    // This method gets called by the runtime. Use this method to configure the HTTP request pipeline.
    public void Configure(IApplicationBuilder app, IWebHostEnvironment env)
    {
        if (env.IsDevelopment())
            app.UseDeveloperExceptionPage();

        app.UseRouting();

        app.UseEndpoints(endpoints =>
        {
            endpoints.MapGrpcService<GrpcStatisticsService>();

            endpoints.MapGet("/", async context =>
            {
                await context.Response.WriteAsync("Communication with gRPC endpoints must be made through a gRPC client.");
            });
        });
    }
}
